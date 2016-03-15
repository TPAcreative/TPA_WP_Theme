<?php
function tpa_meta_boxes() {

    $prefix = 'tpa_';

    // Page Options
    $page_options_meta = array(
        'id' => 'page-options',
        'title' => 'Page Options',
        'pages' => array('page'),
        'context' => 'normal',
        'priority' => 'low',
        'fields' => array(
            array(
                'label' => 'Summary',
                'desc' => 'For child pages using boxed navigation',
                'id' => $prefix . 'page_summary',
                'type' => 'text'
            ),
            array(
                'label' => 'Insert Slider',
                'desc' => 'Select a slider to display in the header',
                'id' => $prefix . 'revolution_slider',
                'type' => 'revolution_slider'
            ),
            array(
                'label' => 'Website Feedback',
                'desc' => 'For use by the Contact page only',
                'id' => $prefix . 'page_website_feedback',
                'type' => 'text'
            ),
            array(
                'label' => 'Child Page Columns',
                'desc' => 'Enter the number of columns allocated to the boxed navigation of child pages',
                'id' => $prefix . 'child_col_num',
                'type' => 'text'
            )
        )
    );

    // People Options
    $people_options_meta = array(
        'id' => 'people-options',
        'title' => 'People Options',
        'pages' => array('people'),
        'context' => 'normal',
        'priority' => 'low',
        'fields' => array(
            array(
                'id' => $prefix . 'person_role',
                'label' => 'Role',
                'desc' => 'Select a role for this person',
                'type' => 'role',
            ),
            array(
                'id' => $prefix . 'person_partner_salaried',
                'label' => 'Is the Partner Salaried?',
                'desc' => 'Only applicable if the role is set to Partner',
                'type' => 'select',
                'choices' => array(
                    array(
                        'label' => 'No',
                        'value' => 'no'
                    ),
                    array(
                        'label' => 'Yes',
                        'value' => 'yes'
                    )
                )
            ),
            array(
                'id' => $prefix . 'person_job_title',
                'label' => 'Job Title',
                'desc' => 'If this person\'s Job Title is different to their role, enter it here.',
                'type' => 'text',
            ),
            array(
                'id' => $prefix . 'people_banner_text',
                'label' => 'Banner Summary',
                'desc' => 'Enter a short summary for this person. This text will appear in the banner.',
                'type' => 'textarea',
            ),
            array(
                'id' => $prefix . 'people_practice_areas',
                'label' => 'Practice Areas',
                'desc' => 'Enter the practice areas relevant to this person. Note: Each new line represents an item in the list',
                'type' => 'textarea',
            ),
            array(
                'id' => $prefix . 'people_email',
                'label' => 'Email Address',
                'desc' => 'Enter the email address to contact this person.',
                'type' => 'text',
            ),
            array(
                'id' => $prefix . 'people_telephone_ddi',
                'label' => 'Telephone (DDi)',
                'desc' => 'Enter a telephone number to contact this person.',
                'type' => 'text',
            ),
            array(
                'id' => $prefix . 'people_telephone',
                'label' => 'Telephone',
                'desc' => 'Enter a DDI (Direct Dial in) number to contact this person.',
                'type' => 'text',
            ),
            array(
                'id' => $prefix . 'people_telephone_additional',
                'label' => 'Telephone (Additional)',
                'desc' => 'Enter a telephone number to contact this person. Append a description in brackets to the number e.g. +27 21 880 9391 (South Africa)',
                'type' => 'text',
            ),
            array(
                'id' => $prefix . 'people_mobile',
                'label' => 'Mobile',
                'desc' => 'Enter a mobile number to contact this person.',
                'type' => 'text',
            ),
            array(
                'id' => $prefix . 'people_location',
                'label' => 'Location',
                'desc' => 'Select a location for the individual. This list is populated by each location created within the "Locations" section',
                'type' => 'location',
            ),
            array(
                'id' => $prefix . 'people_location_checkbox',
                'label' => 'Location',
                'desc' => 'Select a location for the individual. This list is populated by each location created within the "Locations" section',
                'type' => 'location-checkbox',
            ),
            array(
                'id' => $prefix . 'people_alt_image',
                'label' => 'Alternative Banner Image',
                'desc' => 'Upload a file to replace the current banner image for this individual within their profile. <strong>IMPORTANT:</strong> When inserting the image, ensure the image ID is inserted, not the URL (Media File Link).',
                'type' => 'upload',
            )
        )
    );

    ot_register_meta_box( $page_options_meta );
    ot_register_meta_box( $people_options_meta );

}
add_action( 'admin_init', 'tpa_meta_boxes' );

?>
