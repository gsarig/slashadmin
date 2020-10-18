<?php
$enabled = slash_admin( 'taxonomy_order' );
if($enabled) {
	new SlashAdmin\TaxonomyOrder();
}
