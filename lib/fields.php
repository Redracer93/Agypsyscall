<?php
/* Field Types */

function cbaffmach_field_text( $label = '', $name = '', $value = '', $id = false, $class = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input type="text" class="regular-text <?php echo $class;?>" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?> value="<?php echo stripslashes( $value ); ?>">
            <?php if( !empty( $help ) ) { ?>
                <p class="description"><?php echo $help;?></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function cbaffmach_field_password( $label = '', $name = '', $value = '', $id = false, $classes = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input type="password" class="regular-text" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?> value="<?php echo stripslashes( $value ); ?>">
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>
<?php
}

function cbaffmach_field_textarea( $label = '', $name = '', $value = '', $id = false, $class = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <textarea class="<?php echo $class;?>" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?>><?php echo stripslashes( $value ); ?></textarea>
            <?php if( !empty( $help ) ) { ?>
                <p class="description"><?php echo $help;?></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function cbaffmach_field_checkbox( $label = '', $name = '', $value = '', $id = false, $class = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {
    // var_dump($value);
    if ( $value )
        $checked = checked( $value, 1, false );
    else
        $checked = '';
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
    if( !empty( $class ) )
        $class = ' class="'.$class.'"';
    ?>
        <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
            <th scope="row"><?php echo $label;?></th>
            <td>
                <input type="checkbox" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $class;?> <?php echo $checked;?> value="1">
                <?php if( !empty( $help ) ) { ?>
                    <p class="description"><?php echo $help;?></p>
                <?php } ?>
            </td>
        </tr>
<?php
}

function cbaffmach_field_select( $label = '', $name = '', $value = '', $values = array(), $id = false, $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false, $extra_option = false ) {
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <select <?php echo $id;?> class="<?php echo $class; ?>" name="<?php echo $name;?>"><?php cbaffmach_select_options( $value, $values, $extra_option );?></select>
            <?php if( !empty( $help ) ) { ?>
                <p class="description"><?php echo $help;?></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function cbaffmach_field_image( $label = '', $name = '', $value, $id = false, $placeholder = '', $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false ) {

    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';

    $hide_img = false;
?>
    <tr valign="top" class="file-upload-parent <?php echo $row_class;?>" <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input class="regular-text file-upload-url" type="text" size="36" name="<?php echo $name;?>" value="<?php echo $value;?>" placeholder="Select file..." />
            <button class="button button-secondary cbaffmach_img_upload" type="button"><span class="fa fa-upload"></span> Select Image</button>
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
            <?php if (!$hide_img) { ?>
                <br/>
                <br/>
                <img src="<?php echo $value; ?>" alt="" class="file-upload-img cbaffmach-banner-img">
            <?php } ?>
        </td>
    </tr>
<?php
}
/* Helper functions */

function cbaffmach_select_options( $current_value = 0, $values=array(), $extra_option = false ) {
    if ($values) {
        if ($extra_option) {
            if( is_string( $extra_option ) )
                $txt = $extra_option;
            else
                $txt = 'Select...';
            ?>
                <option value="0"><?php echo $txt; ?></option>
            <?php
        }
        foreach ($values as $value) {
            // var_dump($value);
            if ($value['value'] === 'optgroup' ) {
                ?>
                    <optgroup label="<?php echo $value['label'];?>">
                <?php
            }   else if (/*$value['value'] &&*/ $value['value'] === 'optgroupclose' ) {
                    ?>
                    </optgroup>
            <?php
                } else {
            ?>
                <option value="<?php echo $value['value'];?>" <?php cbaffmach_selected( $current_value, $value['value'] );?>><?php echo $value['label'];?></option>
            <?php
            }
        }
    }
}

function cbaffmach_checked($current_value, $compare_value=true, $echo=1) {
    if ( $current_value == $compare_value ) {
        if ($echo) {
            echo ' checked="checked" ';
        }
        return ' checked="checked" ';
    }
}

function cbaffmach_selected( $current_value, $compare_value = 'true', $echo = 1 ) {
    if ( $current_value && ( $current_value == $compare_value ) ) {
        if ($echo) {
            echo ' selected="selected" ';
        }
        return ' selected="selected" ';
    }
}

function cbaffmach_get_selected_in_array($haystack, $needle) {
    if ( $haystack ) {
        foreach ( $haystack as $element ) {
            if ( $element['value'] == $needle )
                return $element['label'];
        }
    }
    return '';
}

function cbaffmach_posttype_checked( $pt_name, $values ) {
    if( empty( $values ) )
        return false;
    foreach( $values as $key => $value ) {
        if( $key == $pt_name )
            return true;
    }
    return false;
}

function cbaffmach_field_hidden( $name = '', $value = '', $id = '', $class='' ) {
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    echo '<input type="hidden" name="'.$name.'" value="'.$value.'" class="'.$class.'" />';
}


function cbaffmach_cats_dropdown( $name, $val ) {
    $args = array(
        'show_option_none' => __( 'Select category' ),
        'show_count'       => 1,
        'hide_empty'       => 0,
        'name'               => $name,
        'selected'      => $val,
        'orderby'          => 'name',
        'echo'             => 0,
    );

    $select  = wp_dropdown_categories( $args );
    return $select;
}

function cbaffmach_field_static( $label = '', $value = '', $id = false, $classes = '',  $help = '', $row_class = '', $row_visible = true ) {
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <span><?php echo stripslashes( $value ); ?></span>
            <?php if( !empty( $help ) ) { ?>
                <p class="description"><?php echo $help;?></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function cbaffmach_field_bannerpos( $label = '', $name = '', $vvalues = array(), $id = false, $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false, $extra_option = false ) {

    // if( empty( $values ) )
        $values = cbaffmach_get_banner_positions();
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    // if( empty( $value ) )
    //     $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
    if( isset( $vvalues[0] ) )
        $value = intval( $vvalues[0] );
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <select <?php echo $id;?> class="<?php echo $class; ?> cbaffmach_banner_pos" name="<?php echo $name[0];?>"><?php cbaffmach_select_options( $value, $values, $extra_option );?></select>
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>

    <?php

        if( $value != 4 )
            $paragraph_hidden = true;
        else
            $paragraph_hidden = false;
        // if ( isset( $settings->paragraph ) )
        //     $value = intval( $settings->paragraph );
        // else
        //     $value = 1;
        if( isset( $vvalues[1] ) )
            $value = intval( $vvalues[1] );
        $help = false;
    ?>
    <tr valign="top" class="cbaffmach_paragraph_row" <?php if( $paragraph_hidden || $hidden ) echo 'style="display:none"';?>>
        <th scope="row">Paragraph</th>
        <td>
            <input type="number" class="small-text" name="<?php echo $name[1];?>" value="<?php echo $value; ?>">
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>

    <?php
        /*if ( isset( $settings->float ) )
            $value = $settings->float;
        else
            $value = '';
        $values = cbaffmach_get_banner_float();
        $help = false;*/
    ?>
<!--     <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row">Float</th>
        <td>
            <select class="<?php echo $class; ?>" name="<?php echo $name[2];?>"><?php cbaffmach_select_options( $value, $values, $extra_option );?></select>
                <span class="description">If you want the text content of the article to be inline with your ad, select left/right. Otherwise leave None.</span>
        </td>
    </tr> -->

    <?php
        // if ( isset( $settings->margin ) )
        //     $value = intval( $settings->margin );
        // else
        //     $value = 0;
        if( isset( $vvalues[2] ) )
            $value = intval( $vvalues[2] );
        $help = false;
    ?>

    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row">Margin</th>
        <td>
            <input type="number" class="small-text" name="<?php echo $name[3];?>" value="<?php echo $value; ?>"> pixels
                <span class="description" style="padding-left: 15px">Blank space (in pixels) between your ads and the post content </span>
        </td>
    </tr>
<?php
}
?>