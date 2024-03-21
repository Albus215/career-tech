<?php

//custom gravity forms

add_filter('gform_add_field_buttons', 'register_my_custom_field');
function register_my_custom_field($field_groups)
{
	foreach ($field_groups as &$group) {
		if ($group['name'] == 'standard_fields') {
			$group['fields'][] = array(
				'class'     => 'button',
				'value'     => __('Image Test Block', 'gravityforms'),
				'onclick'   => "StartAddField('image_test_block');",
				'data-type' => 'image_test_block'
			);
			break;
		}
	}
	return $field_groups;
}

add_action('gform_field_standard_settings', 'image_test_block_settings', 10, 2);
function image_test_block_settings($position, $form_id)
{
	// Show settings only for our field type
	if ($position == 0) {
?>
		<li class="image_test_block_setting field_setting">
			<label for="field_image_test_block_label_<?php echo $form_id; ?>" class="section_label">
				<?php _e('Image Test Block Settings', 'gravityforms'); ?>
			</label>
			<span>Title</span><br />
			<input type="text" id="field_image_test_block_title" class="fieldwidth-3" onchange="SetFieldProperty('imageTestBlockTitle', this.value);" /><br />

			<span>Description</span><br />
			<textarea id="field_image_test_block_description" class="fieldwidth-3" rows="4" onchange="SetFieldProperty('imageTestBlockDescription', this.value);"></textarea><br />

			<input id="field_image_test_block_image" type="hidden" onchange="SetFieldProperty('imageTestBlockImage', this.value);">
			<input type="button" value="<?php _e("Select Image", "gravityforms"); ?>" class="button" onclick="OpenMediaLibrary(this);" />
		</li>
		<script>
			function OpenMediaLibrary(button) {
				var frame = wp.media({
					title: 'Select Image',
					button: {
						text: 'Insert'
					},
					multiple: false
				});
				frame.on('select', function() {
					// get the selected image and save its URL
					var attachment = frame.state().get('selection').first().toJSON();
					// We use the parent element to find the corresponding hidden input field and update its value
					jQuery(button).prev('input').val(attachment.url).change();
				});
				frame.open();
			}
		</script>
	<?php
	}
}

add_filter('gform_field_content', 'display_image_test_block_content', 10, 5);
function display_image_test_block_content($content, $field, $value, $lead_id, $form_id) {
    //  modify only our field type 'image_test_block'
    if ($field->type == 'image_test_block') {
        $title = !empty($field->imageTestBlockTitle) ? $field->imageTestBlockTitle : 'Заголовок не вказано';
        $description = !empty($field->imageTestBlockDescription) ? $field->imageTestBlockDescription : 'Опис не вказано';
        $image = !empty($field->imageTestBlockImage) ? $field->imageTestBlockImage : 'https://via.placeholder.com/150'; // Посилання на заміщувальне зображення

        // Generate HTML field content
        $content = "<div class='image_test_block_wrapper'>";
        $content .= "<img src='{$image}' alt='Image Test Block Image'/>";
        $content .= "<h3>{$title}</h3>";
        $content .= "<p>{$description}</p>";
        $content .= "</div>";
    }

    return $content;
}


add_action('gform_editor_js', 'image_test_block_editor_js');
function image_test_block_editor_js()
{
	?>
	<script type='text/javascript'>
		fieldSettings.image_test_block = '.label_setting, .admin_label_setting, .description_setting, .css_class_setting, .image_test_block_setting';

		jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
			jQuery("#field_image_test_block_title").val(field["imageTestBlockTitle"]);
			jQuery("#field_image_test_block_description").val(field["imageTestBlockDescription"]);
			jQuery("#field_image_test_block_image").val(field["imageTestBlockImage"]);
		});
	</script>
<?php
}