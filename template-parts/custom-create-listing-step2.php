<?php
global $current_user, $houzez_local, $hide_prop_fields, $required_fields, $is_multi_steps;
$is_multi_currency = houzez_option('multi_currency');
$default_multi_currency = get_the_author_meta( 'fave_author_currency' , $current_user->ID );
if(empty($default_multi_currency)) {
    $default_multi_currency = houzez_option('default_multi_currency');
}
$adp_details_fields = houzez_option('adp_details_fields');
$fields_builder = $adp_details_fields['enabled'];
unset($fields_builder['placebo']);

$prop_type =   $_POST['prop_type'];
if (!function_exists('get_custom_field_from_field_builder')) {
    function get_custom_field_from_field_builder($key) {
        if (in_array($key, houzez_details_section_fields())) {
            if ($key == 'property-id') {
                if ($auto_property_id != 1) {
                    echo '<div class="col-md-6 col-sm-12">';
                    get_template_part('template-parts/dashboard/submit/form-fields/' . $key);
                    echo '</div>';
                }
            } else {
                echo '<div class="col-md-6 col-sm-12">';
                get_template_part('template-parts/dashboard/submit/form-fields/' . $key);
                echo '</div>';
            }
        } else {
            echo '<div class="col-md-6 col-sm-12">';
            houzez_get_custom_add_listing_field($key);
            echo '</div>';
        }
    }
}

if (!function_exists('get_single_select_custom')) {
    function get_single_select_custom($key) {
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
        if ($field_type == 'select') { ?>
            <div class="form-group">
                <label for="<?php echo esc_attr($field_name); ?>">
                    <?php echo $field_title . houzez_required_field($field_name); ?>
                </label>
                <select name="<?php echo esc_attr($field_name); ?>" data-size="5" class="selectpicker <?php houzez_required_field_2($field_name); ?> form-control bs-select-hidden" title="<?php echo esc_attr($placeholder); ?>" data-live-search="false">
                    <?php
                    $options = unserialize($field_options);
                    if ($selected) { ?>
                        <option <?php echo esc_attr($selected); ?> value=""><?php echo esc_attr($placeholder); ?> </option>
                    <?php } else {
                        echo '<option value="">' . houzez_option('cl_select', 'Select') . '</option>';
                    }
                    foreach ($options as $key => $val) {
                        $val = houzez_wpml_translate_single_string($val);
                        $selected_val = houzez_get_field_meta($field_name);
                        $key = trim($key);
                        echo '<option ' . selected($selected_val, $key, false) . ' value="' . esc_attr($key) . '">' . esc_attr($val) . '</option>';
                    }
                    ?>
                </select>
            </div>
        <?php
        } else {
            houzez_get_custom_add_listing_field($key);
        }
        echo '</div></div>';
    }
}
if ($fields_builder && $prop_type) {
    $term = get_term( $prop_type);
    ?>
    <script>
        // console.log(<?php echo json_encode($term);?>);        
    </script>
    <div class="dashboard-content-block prop-specific-form-wrapper">
        <h3><?= $term->name;?></h3>
        <?php
        /** Office Building */
        if($term->term_id == 171): ?>
            <div class="row">
                <?php                
                $key = 'lot-area';
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
                $key = 'gross-floor-area';
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
                $key = 'total-leasable-area';
                if (in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php
                            if (in_array($key, houzez_details_section_fields())) {
                                get_custom_field_from_field_builder($key);
                            }
                            ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
    
                $key = 'average-floorplate';
                if (in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php
                            if (in_array($key, houzez_details_section_fields())) {
                                get_custom_field_from_field_builder($key);
                            }
                            ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
    
                $key = 'building-class';
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
                $key = 'leed-certification';
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
    
                $key = 'well-certification';
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
    
                $key = 'peza-certification';
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
    
                $key = 'other-awards-certification';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
    
                $key = 'availabilty';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
    
                $key = 'average-lease-rate';
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
    
                $key = 'average-selling-price';
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
    
                $key = 'is-the-property-part-of-a-mixed-use-development';
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
    
                $key = 'within-the-mixed-use-development';
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
                } ?> 
            </div>
        <?php
        /** Office Space only */
        elseif($term->term_id == 172): ?>
            <div class="row">
                <?php
                $key = 'unit';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                $key = 'floor-level';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'office-building-name';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'size';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'net-usable-area';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'well-certification';
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

                $key = 'peza-certification';
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

                $key = 'other-awards-certification';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>
            </div>
            <h4>Leasing Rates</h4>
            <div class="row">
                <?php
                $key = 'rental-rate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'association-dues';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'c-charges';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'parking-slot-allocation';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'parking-rental-rate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'parking-association-dues';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>
            </div>
            <h4>Selling Price</h4>
            <div class="row">
                <?php
                $key = 'price-per-sqm';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'total-selling-price';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'cap-rate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'payment-terms';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>

            </div>
        <?php
        /** Warehouse Building */
        elseif($term->term_id == 118): ?>
            <div class="row">
                <?php
                $key = 'size';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'net-usable-area';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'clear-height';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'shoulder';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'apex';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'warehouse-lighting-type';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'no-of-loading-bays';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'loading-door-height';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'fire-supression-system';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'with-office';
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

                $key = 'office-size';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'common-toilet';
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

                $key = 'parking-area';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'availabilty';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'is-the-building-leased-as-a-whole';
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

                $key = 'average-lease-rate';
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

                $key = 'average-selling-price';
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
            <h4>Lease Rates</h4>
            <div class="row">
                <?php
                $key = 'indoor-rental-rate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'outdoor-area-rental-rate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'association-dues';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>

            </div>
            <h4>Selling Price</h4>
            <div class="row">
                <?php
                $key = 'price-per-sqm';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'total-selling-price';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'cap-rate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'payment-terms';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>

            </div>
        <?php 
        /** Industrial Land */
        elseif($term->term_id == 175): ?>
            <div class="row">
                <?php
                $key = 'total-land-area';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'within-industrial-estate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'utilities-available';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'open-for-subdividing';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'land-building-restrictions';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'floor-area-ratio';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>

            </div>
            <h4>Lease Rates</h4>
            <div class="row">
                <?php
                $key = 'indoor-rental-rate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'association-dues';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>

            </div>
            <h4>Selling Price</h4>
            <div class="row">
                <?php
                $key = 'price-per-sqm';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'total-selling-price';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'cap-rate';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }

                $key = 'payment-terms';
                if (function_exists('get_custom_field_from_field_builder') && in_array($key, houzez_details_section_fields())) { ?>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php get_custom_field_from_field_builder($key); ?>
                        </div>
                    </div>
                <?php } else {
                    echo '<div class="col-md-4 col-sm-12">';
                    echo '<div class="form-group">';
                    houzez_get_custom_add_listing_field($key);
                    echo '</div></div>';
                }
                ?>

            </div>        
        <?php
        endif;
        ?>
    </div>
    <?php 
} else { ?>
    <div class="dashboard-content-block">
        Please go back and select the required fields.
    </div>
<?php }

?>

