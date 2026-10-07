#!/usr/bin/env node
/**
 * SSH a Hostinger desde el entorno cloud, a través del proxy HTTPS (CONNECT) del entorno.
 * Credenciales SOLO por variables de entorno (secretos del entorno), nunca en el repo:
 *   IRINA_SSH_HOST, IRINA_SSH_PORT (65002 en Hostinger), IRINA_SSH_USER,
 *   IRINA_SSH_PASSWORD  o  IRINA_SSH_KEY (clave privada OpenSSH completa)
 *
 * Uso:
 *   node tools/ssh.mjs "wp core version --path=domains/drairinagonzalez.com/public_html"
 *   node tools/ssh.mjs --put local.txt remoto/ruta.txt
 *   node tools/ssh.mjs --get remoto/ruta.txt local.txt
 */
import net from 'node:net';
import fs from 'node:fs';
import { Client } from 'ssh2';

const env = process.env;
const host = env.IRINA_SSH_HOST;
const port = Number(env.IRINA_SSH_PORT || 22);
const username = env.IRINA_SSH_USER;
if (!host || !username || (!env.IRINA_SSH_PASSWORD && !env.IRINA_SSH_KEY)) {
  console.error('Faltan secretos: IRINA_SSH_HOST, IRINA_SSH_USER y IRINA_SSH_PASSWORD o IRINA_SSH_KEY.');
  process.exit(2);
}

function proxyTunnel(targetHost, targetPort) {
  return new Promise((resolve, reject) => {
    const proxy = (env.HTTPS_PROXY || env.https_proxy || '').replace(/^https?:\/\//, '');
    if (!proxy) return resolve(net.connect(targetPort, targetHost));
    const [ph, pp] = proxy.split(':');
    const sock = net.connect(Number(pp), ph, () => {
      sock.write(`CONNECT ${targetHost}:${targetPort} HTTP/1.1\r\nHost: ${targetHost}:${targetPort}\r\n\r\n`);
    });
    let buf = '';
    const onData = (d) => {
      buf += d.toString('latin1');
      const end = buf.indexOf('\r\n\r\n');
      if (end === -1) return;
      sock.removeListener('data', onData);
      const status = buf.split('\r\n')[0];
      if (!/ 200 /.test(status)) { sock.destroy(); return reject(new Error('Proxy rechazó CONNECT: ' + status)); }
      const rest = Buffer.from(buf.slice(end + 4), 'latin1');
      if (rest.length) sock.unshift(rest);
      resolve(sock);
    };
    sock.on('data', onData);
    sock.on('error', reject);
  });
}

function connect() {
  return new Promise(async (resolve, reject) => {
    let sock;
    try { sock = await proxyTunnel(host, port); } catch (e) { return reject(e); }
    const conn = new Client();
    conn.on('ready', () => resolve(conn)).on('error', reject);
    const auth = env.IRINA_SSH_KEY ? { privateKey: env.IRINA_SSH_KEY.replace(/\\n/g, '\n') } : { password: env.IRINA_SSH_PASSWORD };
    conn.connect({ sock, username, readyTimeout: 30000, ...auth });
  });
}

function exec(conn, cmd) {
  return new Promise((resolve, reject) => {
    conn.exec(cmd, (err, stream) => {
      if (err) return reject(err);
      let code = 0;
      stream.on('close', (c) => resolve(c ?? code));
      stream.on('data', (d) => process.stdout.write(d));
      stream.stderr.on('data', (d) => process.stderr.write(d));
    });
  });
}

function sftp(conn) {
  return new Promise((resolve, reject) => conn.sftp((err, s) => (err ? reject(err) : resolve(s))));
}

const args = process.argv.slice(2);
const conn = await connect().catch((e) => { console.error('No se pudo conectar:', e.message); process.exit(1); });
try {
  if (args[0] === '--put') {
    const s = await sftp(conn);
    await new Promise((res, rej) => s.fastPut(args[1], args[2], (e) => (e ? rej(e) : res())));
    console.log('subido', args[2]);
  } else if (args[0] === '--get') {
    const s = await sftp(conn);
    await new Promise((res, rej) => s.fastGet(args[1], args[2], (e) => (e ? rej(e) : res())));
    console.log('descargado', args[2]);
  } else {
    const code = await exec(conn, args.join(' ') || 'echo ok');
    process.exitCode = code;
  }
} finally {
  conn.end();
}
