<?php
/** Isolated Rank Math duplicate-filter fixture; no database, network or credentials. */
declare(strict_types=1);
require __DIR__.'/../../wp-content/plugins/dra-irina-core/src/Schema/Graph.php';
$input=['person'=>['@type'=>'Person'],'dual'=>['@type'=>['Physician','Person']],'org'=>['@type'=>'Organization'],'web'=>['@type'=>'WebSite'],'page'=>['@type'=>'WebPage'],'crumb'=>['@type'=>'BreadcrumbList'],'image'=>['@type'=>'ImageObject']];
$result=(new DraIrina\Core\Schema\Graph())->strip_rank_math_duplicates($input);
$expected=['web','page','crumb','image'];
if(array_keys($result)!==$expected)throw new RuntimeException('Duplicate filter removed navigation schema or kept professional duplicates');
$rows=['professional_duplicates_removed'=>!isset($result['person'],$result['dual'],$result['org']),'website_page_crumb_image_retained'=>array_keys($result)===$expected,'limits'=>'Isolated filter; public node count tested separately; not a new external schema.org validation.'];
file_put_contents(__DIR__.'/../../docs/audit/2026-10-16/schema.json',json_encode($rows,JSON_PRETTY_PRINT)."\n");
echo "Schema duplicate filter passes\n";
