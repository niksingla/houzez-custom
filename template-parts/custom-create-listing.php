<?php
global $current_user, $houzez_local, $hide_prop_fields, $required_fields, $is_multi_steps;
$is_multi_currency = houzez_option('multi_currency');
$default_multi_currency = get_the_author_meta( 'fave_author_currency' , $current_user->ID );
if(empty($default_multi_currency)) {
    $default_multi_currency = houzez_option('default_multi_currency');
}

/** Add taxonomies programatically */
function add_property_types_to_taxonomy() {
    // Define the terms you want to add
    $property_types = [
        'Office Building',
        'Office Space Only',
        'Serviced Office',
        'Industrial Park/Estate',
        'Warehouse Building',
        'Industrial Land',
        'Commercial Mall',
        'Commercial Shop/Kiosk',
        'Retail Space',
        'Commercial Land',
        'Land',
        'Hotel',
        'Vacation Homes',
        'Whole Estate Development',
        'RE with Income',
        'Events & Multi Spaces'
    ];

    // Check if the taxonomy exists
    if (taxonomy_exists('property_type')) {
        foreach ($property_types as $type) {
            // Add the term if it doesn't already exist
            if (!term_exists($type, 'property_type')) {
                wp_insert_term($type, 'property_type');
            }
        }
    }
}

$adp_details_fields = houzez_option('adp_details_fields');
$fields_builder = $adp_details_fields['enabled'];
unset($fields_builder['placebo']);

if ($fields_builder) {

    function get_custom_field_from_field_builder($key){
        if(in_array($key, houzez_details_section_fields())) { 
    
            if( $key == 'property-id' ) {
    
                if( $auto_property_id != 1 ) {
                    echo '<div class="col-md-6 col-sm-12">';
                        get_template_part('template-parts/dashboard/submit/form-fields/'.$key); 
                    echo '</div>';
                }
    
            } else {
                echo '<div class="col-md-6 col-sm-12">';
                    get_template_part('template-parts/dashboard/submit/form-fields/'.$key); 
                echo '</div>';
            }
            
    
        } else {
    
            echo '<div class="col-md-6 col-sm-12">';
                houzez_get_custom_add_listing_field($key);
            echo '</div>';
        }
    }

    function get_single_select_custom($key){
        echo '<div class="col-md-4 col-sm-12">';
        echo '<div class="form-group">';  
        $field_array = Houzez_Fields_Builder::get_field_by_slug($key);
        $field_title = houzez_wpml_translate_single_string($field_array['label']);
        $placeholder = houzez_wpml_translate_single_string($field_array['placeholder']);
        $field_name = $field_array['field_id'];
        $field_type = $field_array['type'];
        $field_options = $field_array['fvalues'];
        $selected = false;
        if (houzez_edit_property()) {
            $selected = 'selected=selected';
        }
        if($field_type == 'select' ) { ?>
            <div class="form-group">
                <label for="<?php echo esc_attr($field_name); ?>">
                    <?php echo $field_title.houzez_required_field($field_name); ?>
                </label>
                <select name="<?php echo esc_attr($field_name);?>" data-size="5" class="selectpicker <?php houzez_required_field_2($field_name); ?> form-control bs-select-hidden" title="<?php echo esc_attr($placeholder); ?>" data-live-search="false">
                    <?php
                    $options = unserialize($field_options);
                    if($selected){
                        ?>
                        <option <?php echo esc_attr($selected); ?> value=""><?php echo esc_attr($placeholder); ?> </option>
                        <?php
                    } else echo '<option value="">'.houzez_option('cl_select', 'Select').'</option>';                                
                    
                    foreach ($options as $key => $val) {
                        $val = houzez_wpml_translate_single_string($val);                                    
                        $selected_val = houzez_get_field_meta($field_name);
                        $key = trim($key);        
                        echo '<option '.selected($selected_val, $key, false).' value="'.esc_attr($key).'">'.esc_attr($val).'</option>';
                    }
                    ?>
                </select><!-- selectpicker --> 
            </div>
        <?php
        } else {
            houzez_get_custom_add_listing_field($key);
        }
        echo '</div></div>';
    }

    ?>
    <style>
        .bootstrap-select .dropdown-menu{
            width: 100%;
        }
        .custom-dashboard-layout .upload-media-gallery{
            margin-top:0;
        }
        .upload-media-gallery .upload-gallery-thumb-buttons .icon{
            padding:0;
            margin:0;
        }
        .dashboard-add-new-listing .add-new-listing-bottom-nav-wrap{
            z-index: 10;
        }
        .media-drag-drop{
            min-height:308px;
        }
        span.ft-img-notice {
            position: absolute;
            width: 100%;
            left: 0;
            bottom: -7rem;
            padding: 0 10px;
        }
        .prop-specific-form-wrapper h3 {
            font-weight: 600;
            font-size: 24px;
        }
        .prop-specific-form-wrapper h4 {
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .dashboard-content-block-wrap label {
            max-width: 270px;
            font-weight: 400;
            font-size: 14px;
            line-height: 20px;
        }
        <?php
        if(! is_admin()){ 
            echo '.dashboard-content-wrap .order-2{
                display:none!important;
            }';
        } ?>
    </style>
    <div id="description-price" class="dashboard-content-block-wrap custom-dashboard-layout next-variable-form <?php echo esc_attr($is_multi_steps);?>">
        <div class="dashboard-content-block">
            <div class="row">
                <?php if( $hide_prop_fields['prop_type'] != 1 ) { ?>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="prop_type">Property Type</label>                    
                        <select name="prop_type[]" data-size="5" <?php houzez_required_field_2('prop_type'); ?> id="prop_type" class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_select', 'Select'); ?>" data-selected-text-format="count > 2" data-live-search-normalize="true" data-live-search="true" data-actions-box="true" <?php houzez_multiselect(houzez_option('ams_type', 0)); ?> data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}" data-count-selected-text="{0} <?php echo houzez_option('cl_prop_types', 'Types'); ?>">
                            
                            <?php
                            if( !houzez_is_multiselect(houzez_option('ams_type', 0)) ) {
                                echo '<option value="">'.houzez_option('cl_select', 'Select').'</option>';
                            }
                            if (houzez_edit_property()) {
                                global $property_data;
                                houzez_get_taxonomies_for_edit_listing_multivalue( $property_data->ID, 'property_type');
    
                            } else {
                                                
                            $property_types_terms = get_terms (
                                array(
                                    "property_type"
                                ),
                                array(
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'parent' => 0
                                )
                            );
    
                            houzez_get_taxonomies_with_id_value( 'property_type', $property_types_terms, -1);
                            }
                            ?>
    
                        </select><!-- selectpicker -->
                    </div><!-- form-group -->
                </div>
                <?php } ?>        
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="cus_location">Location</label>
                        <input name="cus_location" type="text" id="cus_location" class="form-control" placeholder="Add location">
                    </div>
                </div>
                <?php                
                $key = 'region';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?> 
                <?php 
                $key = 'city';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>
                <?php 
                $key = 'area';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'nearby-establishments';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'nearby-transportation';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'nearby-hospital';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'nearby-police-station';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'nearby-fire-station';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'nearby-hotels';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'property-size';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'completion-date';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';                    
                        houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = '24-7-access';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    get_single_select_custom($key);
                }
                
                /** Property Status */
                echo '<div class="col-md-4 col-sm-12">';
                echo '<div class="form-group">'; 
                ?>
                <label for="prop_status">
                    <?php echo houzez_option('cl_prop_status', 'Property Status').houzez_required_field('prop_status'); ?>
                </label>

                <select name="prop_status[]" data-size="5" <?php houzez_required_field_2('prop_status'); ?> id="prop_status" class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_select', 'Select'); ?>" data-selected-text-format="count > 2" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}" data-live-search="true"  data-actions-box="true" <?php houzez_multiselect(houzez_option('ams_status', 0)); ?> data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-count-selected-text="{0} <?php echo houzez_option('cl_prop_statuses', 'Statuses'); ?>">
                    <?php
                    if( !houzez_is_multiselect(houzez_option('ams_status', 0)) ) {
                        echo '<option value="">'.houzez_option('cl_select', 'Select').'</option>';
                    }
                    if (houzez_edit_property()) {
                        global $property_data;
                        houzez_get_taxonomies_for_edit_listing_multivalue( $property_data->ID, 'property_status');

                    } else {
                                        
                    $property_status_terms = get_terms (
                        array(
                            "property_status"
                        ),
                        array(
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'parent' => 0
                        )
                    );

                    houzez_get_taxonomies_with_id_value( 'property_status', $property_status_terms, -1);
                    }
                    ?>
                </select><!-- selectpicker -->
                <?php
                echo '</div></div>';

                $key = 'transaction-type';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    get_single_select_custom($key);
                }

                $key = 'handover-condition';
                if(in_array($key, houzez_details_section_fields())){ ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php                                 
                                if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                            ?>
                        </div>
                    </div>
                    <?php
                }else {
                    get_single_select_custom($key);
                }
                ?>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Description</label>
                        <?php
                        $editor_id = 'prop_des';
                        $settings = array(
                            'media_buttons' => false,                            
                            'textarea_rows' => 6,
                        );
                        if (houzez_edit_property()) {
                            global $property_data;
                            wp_editor($property_data->post_content, $editor_id, $settings);
                        } else {
                            wp_editor('', $editor_id, $settings);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row" id="media" data-gallery-reg="<?php echo esc_attr($gallery_image_req); ?>">
                <?php
                $gallery_image_req = houzez_option('gallery_image_req', 1);

                ?>
                <!-- Property Photos -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label>Photos</label>                                            
                    <div class="upload-media-gallery">
                        <div id="houzez_property_gallery_container" class="row">            
                            <?php
                            $property_images_count = 0;
                            if(houzez_edit_property()) {
                                $property_images = get_post_meta( $property_data->ID, 'fave_property_images', false );
        
                                if ( ! empty( $property_images ) ) {
                                    $property_images_count = count( array_filter( $property_images ) );
                                }
            
        
                                $featured_image_id = get_post_thumbnail_id( $property_data->ID );
                                $property_images[] = $featured_image_id;
                                $property_images = array_unique($property_images);
        
                                if( !empty($property_images[0])) {
                                    foreach ($property_images as $prop_image_id) {
        
                                        $is_featured_image = ($featured_image_id == $prop_image_id);
                                        $featured_icon = ($is_featured_image) ? 'text-success' : '';
        
                                        $img_available = wp_get_attachment_image($prop_image_id, 'thumbnail');
        
                                        if( !empty($img_available)) {
                                            echo '<div class="col-md-2 col-sm-4 col-6 property-thumb">';
                                            echo wp_get_attachment_image($prop_image_id, 'houzez-item-image-1', false, array('class' => 'img-fluid'));
                                            echo '<div class="upload-gallery-thumb-buttons">';
                                            echo '<button class="icon icon-fav icon-featured" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '"><i class="houzez-icon icon-rating-star full-star '.esc_attr($featured_icon).'"></i></button>';
    
                                            echo '<button class="icon icon-delete" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '"><span class="btn-loader houzez-loader-js"></span><i class="houzez-icon icon-remove-circle"></i></button>';
                                            echo '</div>';
        
                                            echo '<input type="hidden" class="propperty-image-id" name="propperty_image_ids[]" value="' . intval($prop_image_id) . '"/>';
        
                                            if ($is_featured_image) {
                                                echo '<input type="hidden" class="featured_image_id" name="featured_image_id" value="' . intval($prop_image_id) . '">';
                                            }
                                            
                                            echo '</div>';
                                        }
                                        
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="upload-property-media">
                        <div id="houzez_gallery_dragDrop" class="media-drag-drop">
                            <div class="upload-icon">
                                <i class="houzez-icon icon-picture-sun"></i>
                            </div>
                            <div class="upload-image-counter" bis_skin_checked="1"><span class="uploaded"><?php echo esc_attr($property_images_count); ?></span> / <?php echo houzez_option('max_prop_images'); ?></div>
                            <div style="position:relative;">
                                <?php echo houzez_option('cl_drag_drop_title', 'Drag and drop the gallery images here'); ?><br>
                                <span><?php echo houzez_option('cl_image_size', '(Minimum size 1440x900)'); ?></span><br>
                                <span class="ft-img-notice"><?php echo houzez_option('cl_image_featured', 'To mark an image as featured, click the star icon. If no image is marked as featured, the first image will be considered the featured image.'); ?></span>
                            </div>
                            <a id="select_gallery_images" href="javascript:;" class="btn btn-primary btn-left-icon"><i class="houzez-icon icon-upload-button mr-1"></i> <?php echo houzez_option('cl_image_btn', 'Select and Upload'); ?></a>
                        </div>
                        <div id="houzez_errors"></div>
                        <div class="max-limit-error">The maximum file upload limit has been reached.</div>
                    </div>                    
                </div> 
                <!-- Floorplan Description -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label>Floorplan Description</label>
                    <div class="upload-media-gallery">
                        <div id="houzez_property_floorplan_container" class="row">            
                            <?php
                            $property_images_count = 0;
                            if(houzez_edit_property()) {
                                $property_images = get_post_meta( $property_data->ID, 'fave_floorplan_images', false );
        
                                if ( ! empty( $property_images ) ) {
                                    $property_images_count = count( array_filter( $property_images ) );
                                }
            
        
                                $featured_image_id = get_post_thumbnail_id( $property_data->ID );
                                $property_images[] = $featured_image_id;
                                $property_images = array_unique($property_images);
        
                                if( !empty($property_images[0])) {
                                    foreach ($property_images as $prop_image_id) {
        
                                        $is_featured_image = ($featured_image_id == $prop_image_id);
                                        $featured_icon = ($is_featured_image) ? 'text-success' : '';
        
                                        $img_available = wp_get_attachment_image($prop_image_id, 'thumbnail');
        
                                        if( !empty($img_available)) {
                                            echo '<div class="col-md-2 col-sm-4 col-6 property-thumb">';
                                            echo wp_get_attachment_image($prop_image_id, 'houzez-item-image-1', false, array('class' => 'img-fluid'));
                                            echo '<div class="upload-gallery-thumb-buttons">';
                                            echo '<button class="icon icon-fav icon-featured" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '"><i class="houzez-icon icon-rating-star full-star '.esc_attr($featured_icon).'"></i></button>';
    
                                            echo '<button class="icon icon-delete" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '"><span class="btn-loader houzez-loader-js"></span><i class="houzez-icon icon-remove-circle"></i></button>';
                                            echo '</div>';
        
                                            echo '<input type="hidden" class="propperty-image-id" name="propperty_image_ids[]" value="' . intval($prop_image_id) . '"/>';
        
                                            if ($is_featured_image) {
                                                echo '<input type="hidden" class="featured_image_id" name="featured_image_id" value="' . intval($prop_image_id) . '">';
                                            }
                                            
                                            echo '</div>';
                                        }
                                        
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="upload-property-media">
                        <div id="houzez_gallery_dragDrop2" class="media-drag-drop">
                            <div class="upload-icon">
                                <i class="houzez-icon icon-picture-sun"></i>
                            </div>
                            <div class="upload-image-counter2" bis_skin_checked="1"><span class="uploaded">0</span> / <?php echo houzez_option('max_flpn_images'); ?></div>
                            <div>
                                Drop Floorplan Description Images Here<br>
                                <span>(Minimum size 1440x900)</span><br>                                
                            </div>
                            <a id="select_floorplan_images" href="javascript:;" class="btn btn-primary btn-left-icon"><i class="houzez-icon icon-upload-button mr-1"></i> <?php echo houzez_option('cl_image_btn', 'Select and Upload'); ?></a>
                        </div>
                        <div id="houzez_errors2"></div>
                        <div class="max-limit-error2">The maximum file upload limit has been reached.</div>
                    </div>                    
                </div>
                <!-- Marketing Brochure -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label>Marketing Brochure</label>
                    <div class="upload-media-gallery">
                        <div id="houzez_property_brochure_container" class="row">            
                            <?php
                            $property_images_count = 0;
                            if(false && houzez_edit_property()) {
                                $property_images = get_post_meta( $property_data->ID, 'fave_brochure_images', false );
        
                                if ( ! empty( $property_images ) ) {
                                    $property_images_count = count( array_filter( $property_images ) );
                                }
            
        
                                $featured_image_id = get_post_thumbnail_id( $property_data->ID );
                                $property_images[] = $featured_image_id;
                                $property_images = array_unique($property_images);
        
                                if( !empty($property_images[0])) {
                                    foreach ($property_images as $prop_image_id) {
        
                                        $is_featured_image = ($featured_image_id == $prop_image_id);
                                        $featured_icon = ($is_featured_image) ? 'text-success' : '';
        
                                        $img_available = wp_get_attachment_image($prop_image_id, 'thumbnail');
        
                                        if( !empty($img_available)) {
                                            echo '<div class="col-md-2 col-sm-4 col-6 property-thumb">';
                                            echo wp_get_attachment_image($prop_image_id, 'houzez-item-image-1', false, array('class' => 'img-fluid'));
                                            echo '<div class="upload-gallery-thumb-buttons">';
                                            echo '<button class="icon icon-fav icon-featured" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '"><i class="houzez-icon icon-rating-star full-star '.esc_attr($featured_icon).'"></i></button>';
    
                                            echo '<button class="icon icon-delete" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '"><span class="btn-loader houzez-loader-js"></span><i class="houzez-icon icon-remove-circle"></i></button>';
                                            echo '</div>';
        
                                            echo '<input type="hidden" class="propperty-image-id" name="propperty_image_ids[]" value="' . intval($prop_image_id) . '"/>';
        
                                            if ($is_featured_image) {
                                                echo '<input type="hidden" class="featured_image_id" name="featured_image_id" value="' . intval($prop_image_id) . '">';
                                            }
                                            
                                            echo '</div>';
                                        }
                                        
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="upload-property-media">
                        <div id="houzez_gallery_dragDrop3" class="media-drag-drop">
                            <div class="upload-icon">
                                <i class="houzez-icon icon-picture-sun"></i>
                            </div>
                            <div class="upload-image-counter3" bis_skin_checked="1"><span class="uploaded">0</span> / <?php echo houzez_option('max_mktbrch_files', 1); ?></div>
                            <div>
                                Drop Brochure Files Here<br>
                                <span>(Minimum size 1440x900)</span><br>                                
                            </div>
                            <a id="select_brochure_images" href="javascript:;" class="btn btn-primary btn-left-icon"><i class="houzez-icon icon-upload-button mr-1"></i> <?php echo houzez_option('cl_image_btn', 'Select and Upload'); ?></a>
                        </div>
                        <div id="houzez_errors3"></div>
                        <div class="max-limit-error3">The maximum file upload limit has been reached.</div>
                    </div>                    
                </div>                
                <!-- Seller Photo -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label>Seller Photo</label>
                    <div class="upload-media-gallery">
                        <div id="houzez_property_seller_photo_container" class="row">            
                            <?php
                            $property_images_count = 0;
                            if(false && houzez_edit_property()) {
                                $property_images = get_post_meta( $property_data->ID, 'fave_seller_photo_images', false );
        
                                if ( ! empty( $property_images ) ) {
                                    $property_images_count = count( array_filter( $property_images ) );
                                }
            
        
                                $featured_image_id = get_post_thumbnail_id( $property_data->ID );
                                $property_images[] = $featured_image_id;
                                $property_images = array_unique($property_images);
        
                                if( !empty($property_images[0])) {
                                    foreach ($property_images as $prop_image_id) {
        
                                        $is_featured_image = ($featured_image_id == $prop_image_id);
                                        $featured_icon = ($is_featured_image) ? 'text-success' : '';
        
                                        $img_available = wp_get_attachment_image($prop_image_id, 'thumbnail');
        
                                        if( !empty($img_available)) {
                                            echo '<div class="col-md-2 col-sm-4 col-6 property-thumb">';
                                            echo wp_get_attachment_image($prop_image_id, 'houzez-item-image-1', false, array('class' => 'img-fluid'));
                                            echo '<div class="upload-gallery-thumb-buttons">';
                                            echo '<button class="icon icon-fav icon-featured" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '"><i class="houzez-icon icon-rating-star full-star '.esc_attr($featured_icon).'"></i></button>';
    
                                            echo '<button class="icon icon-delete" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '"><span class="btn-loader houzez-loader-js"></span><i class="houzez-icon icon-remove-circle"></i></button>';
                                            echo '</div>';
        
                                            echo '<input type="hidden" class="propperty-image-id" name="propperty_image_ids[]" value="' . intval($prop_image_id) . '"/>';
        
                                            if ($is_featured_image) {
                                                echo '<input type="hidden" class="featured_image_id" name="featured_image_id" value="' . intval($prop_image_id) . '">';
                                            }
                                            
                                            echo '</div>';
                                        }
                                        
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="upload-property-media">
                        <div id="houzez_gallery_dragDrop4" class="media-drag-drop">
                            <div class="upload-icon">
                                <i class="houzez-icon icon-picture-sun"></i>
                            </div>
                            <div class="upload-image-counter4" bis_skin_checked="1"><span class="uploaded">0</span> / 1</div>
                            <div>
                                Drop Seller Photo Here<br>
                                <span>(Minimum size 1440x900)</span><br>                                
                            </div>
                            <a id="select_seller_photo_images" href="javascript:;" class="btn btn-primary btn-left-icon"><i class="houzez-icon icon-upload-button mr-1"></i> <?php echo houzez_option('cl_image_btn', 'Select and Upload'); ?></a>
                        </div>
                        <div id="houzez_errors4"></div>
                        <div class="max-limit-error4">The maximum file upload limit has been reached.</div>
                    </div>                    
                </div>
                <!-- Video (URL) -->               
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <?php if( $hide_prop_fields['video_url'] != 1 ) { ?>
                    <label><?php echo houzez_option('cls_video', 'Video'); ?></label>
                    <div class="dashboard-content-block">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/video'); ?>
                    </div><!-- dashboard-content-block -->
                    <?php } ?>
                </div>
                <!-- Seller Name -->                
                <?php
                    $key = 'seller-name';
                    if(in_array($key, houzez_details_section_fields())){ ?>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <?php                                 
                                    if(in_array($key, houzez_details_section_fields())) get_custom_field_from_field_builder($key); 
                                ?>
                            </div>
                        </div>
                        <?php
                    }else {
                        echo '<div class="col-md-4 col-sm-12">';
                        echo '<div class="form-group">';                    
                            houzez_get_custom_add_listing_field($key);
                        echo '</div></div>';
                    }
                ?>                
            </div>
             <!-- media wrap -->
        </div>
        <?php if(false){ ?>
            <h2><?php echo houzez_option('cls_description', 'Description'); ?></h2>
    
            <div class="dashboard-content-block">
                <?php get_template_part('template-parts/dashboard/submit/form-fields/title'); ?>
                
                <?php get_template_part('template-parts/dashboard/submit/form-fields/description'); ?>

                <div class="row">
                    <?php if( $hide_prop_fields['prop_type'] != 1 ) { ?>
                    <div class="col-md-4 col-sm-12">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/type'); ?>
                    </div>
                    <?php } ?>

                    <?php if( $hide_prop_fields['prop_status'] != 1 ) { ?>
                    <div class="col-md-4 col-sm-12">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/status'); ?>
                    </div>
                    <?php } ?>

                    <?php if( $hide_prop_fields['prop_label'] != 1 ) { ?>
                    <div class="col-md-4 col-sm-12">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/label'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div><!-- dashboard-content-block -->

            <h2><?php echo houzez_option('cls_price', 'Price'); ?></h2>
            <div class="dashboard-content-block">
                <div class="row">
                    
                    <?php get_template_part('template-parts/dashboard/submit/form-fields/currency'); ?>

                    <?php if( $hide_prop_fields['sale_rent_price'] != 1 ) { ?>
                    <div class="col-md-6 col-sm-12">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/sale-price'); ?>
                    </div><!-- col-md-6 col-sm-12 -->
                    <?php } ?>

                    <?php if( $hide_prop_fields['sale_rent_price'] != 1 && isset( $hide_prop_fields['price_placeholder'] ) && $hide_prop_fields['price_placeholder'] != 1 ) { ?>
                    <div id="price-plac-js" class="col-md-6 col-sm-12">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/price-placeholder'); ?>
                    </div><!-- col-md-6 col-sm-12 -->
                    <?php } ?>

                    <?php if( $hide_prop_fields['second_price'] != 1 ) { ?>
                    <div class="col-md-6 col-sm-12">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/second-price'); ?>
                    </div><!-- col-md-6 col-sm-12 -->
                    <?php } ?>

                    <?php if( $hide_prop_fields['price_postfix'] != 1 ) { ?>
                    <div class="col-md-6 col-sm-12">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/after-price-label'); ?>
                    </div><!-- col-md-6 col-sm-12 -->
                    <?php } ?>

                    <?php if( $hide_prop_fields['price_prefix'] != 1 ) { ?>
                    <div class="col-md-6 col-sm-12">
                        <?php get_template_part('template-parts/dashboard/submit/form-fields/price-prefix'); ?>
                    </div><!-- col-md-6 col-sm-12 -->
                    <?php } ?>
                    
                </div><!-- row -->
            </div><!-- dashboard-content-block -->            
        <?php }
        ?>
    </div><!-- dashboard-content-block-wrap -->

    <!-- Step 2 -->
    <div id="description-price-step2" class="dashboard-content-block-wrap custom-dashboard-layout <?php echo esc_attr($is_multi_steps);?>">
        <div class="loader"></div>
    </div><!-- dashboard-content-block-wrap -->
    
    <?php 
}


?>

