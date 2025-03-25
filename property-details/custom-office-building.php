<?php
$layout = houzez_option('property_blocks');
$layout = isset($layout['enabled']) ? $layout['enabled'] : [];
$meta_type = false;

?>
<div class="property-overview-wrap property-section-wrap" id="property-overview-wrap">
	<div class="block-wrap">
		
		<div class="block-title-wrap d-flex justify-content-between align-items-center">
            <div class="top-tile">
                <h2><?php echo houzez_option('sps_overview', 'Overview'); ?></h2>
                <h2><?php echo houzez_option('spl_prop_id', 'Property ID'); ?>: <span>OB-<?=get_the_ID();?></span></h2>
            </div>			
		</div><!-- block-title-wrap -->

		<div class="d-flex property-overview-data">
			<?php get_template_part('property-details/partials/overview-data'); ?>
			<?php
			$custom_field_value = get_post_meta( get_the_ID(), 'fave_property-size', $meta_type );
			?>
			<!-- <ul class="list-unstyled flex-fill">
				<li class="property-overview-item"><strong>Office Building</strong></li>
				<li class="hz-meta-label property-overview-type">Property Type</li>				
			</ul> -->
		</div><!-- d-flex -->
	</div><!-- block-wrap -->
</div><!-- property-overview-wrap -->
<script>
    console.log(<?php echo json_encode($layout);?>);
    
</script>