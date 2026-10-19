# Quita el aviso de depreciación que el registro de Elementor imprime al terminar (empiece o no en su propia línea)
# y las líneas «Deprecated:» de PHP. Deja intacto lo demás.
/^Deprecated: / { next }
{
	i = index($0, "PHP: 20")
	if (i > 0 && $0 ~ /\[(notice|deprecated|warning) /) {
		pre = substr($0, 1, i - 1)
		if (pre != "") print pre
		s = 1
		next
	}
}
s && /^\)\]/ { s = 0; next }
!s
