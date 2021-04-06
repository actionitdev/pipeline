<?php
/**
 * Genesis Blocks About layout for Slate Collection.
 *
 * @package genesis-blocks
 */

return [
	'type'       => 'layout',
	'key'        => 'gb_slate_layout_about',
	'collection' => [
		'slug'      => 'slate',
		'label'     => __( 'Slate', 'genesis-blocks' ),
		'thumbnail' => 'https://demo.studiopress.com/page-builder/slate/gb_slate_layout_homepage.jpg',
	],
	'content'    => "<!-- wp:genesis-blocks/gb-columns {\"backgroundImgURL\":\"https://demo.studiopress.com/page-builder/slate/gb_slate_hero_background.jpg\",\"backgroundDimRatio\":20,\"focalPoint\":{\"x\":\"0.48\",\"y\":\"0.72\"},\"columns\":1,\"layout\":\"one-column\",\"columnsGap\":1,\"align\":\"full\",\"paddingTop\":5,\"paddingRight\":1,\"paddingBottom\":5,\"paddingLeft\":1,\"paddingUnit\":\"em\",\"customTextColor\":\"#f5f5f5\",\"customBackgroundColor\":\"#1f1f1f\",\"columnMaxWidth\":1200,\"className\":\"gpb-slate-section-hero-title\"} --> <div class=\"wp-block-genesis-blocks-gb-columns gpb-slate-section-hero-title gb-layout-columns-1 one-column gb-has-background-dim gb-has-background-dim-20 gb-background-cover gb-background-no-repeat gb-has-custom-background-color gb-has-custom-text-color gb-columns-center alignfull\" style=\"padding-top:5em;padding-right:1em;padding-bottom:5em;padding-left:1em;background-color:#1f1f1f;color:#f5f5f5;background-image:url(https://demo.studiopress.com/page-builder/slate/gb_slate_hero_background.jpg);background-position:48% 72%\"><div class=\"gb-layout-column-wrap gb-block-layout-column-gap-1 gb-is-responsive-column\" style=\"max-width:1200px\"><!-- wp:genesis-blocks/gb-column --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column\"><div class=\"gb-block-layout-column-inner\"><!-- wp:genesis-blocks/gb-columns {\"columns\":2,\"layout\":\"gb-2-col-wideleft\",\"columnsGap\":8,\"align\":\"full\",\"columnMaxWidth\":1200} --> <div class=\"wp-block-genesis-blocks-gb-columns gb-layout-columns-2 gb-2-col-wideleft gb-columns-center alignfull\"><div class=\"gb-layout-column-wrap gb-block-layout-column-gap-8 gb-is-responsive-column\" style=\"max-width:1200px\"><!-- wp:genesis-blocks/gb-column {\"columnVerticalAlignment\":\"top\"} --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column gb-is-vertically-aligned-top\"><div class=\"gb-block-layout-column-inner\"><!-- wp:heading {\"style\":{\"typography\":{\"fontSize\":60},\"color\":{\"text\":\"#f5f5f5\"}}} --> <h2 class=\"has-text-color\" style=\"font-size:60px;color:#f5f5f5\">About us</h2> <!-- /wp:heading --> <!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#f5f5f5\"}}} --> <p class=\"has-text-color\" style=\"color:#f5f5f5\">The Genesis block pattern library has everything you need to design beautiful block-powered websites with just a few clicks.</p> <!-- /wp:paragraph --></div></div> <!-- /wp:genesis-blocks/gb-column --> <!-- wp:genesis-blocks/gb-column {\"columnVerticalAlignment\":\"center\"} --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column gb-is-vertically-aligned-center\"><div class=\"gb-block-layout-column-inner\"></div></div> <!-- /wp:genesis-blocks/gb-column --></div></div> <!-- /wp:genesis-blocks/gb-columns --></div></div> <!-- /wp:genesis-blocks/gb-column --></div></div> <!-- /wp:genesis-blocks/gb-columns --> <!-- wp:genesis-blocks/gb-columns {\"columns\":2,\"layout\":\"gb-2-col-equal\",\"columnsGap\":3,\"align\":\"full\",\"paddingTop\":6,\"paddingRight\":1,\"paddingBottom\":6,\"paddingLeft\":1,\"paddingUnit\":\"em\",\"customTextColor\":\"#1f1f1f\",\"customBackgroundColor\":\"#ffffff\",\"columnMaxWidth\":1200,\"className\":\"gb-slate-section-numbered-list-and-image\"} --> <div class=\"wp-block-genesis-blocks-gb-columns gb-slate-section-numbered-list-and-image gb-layout-columns-2 gb-2-col-equal gb-has-custom-background-color gb-has-custom-text-color gb-columns-center alignfull\" style=\"padding-top:6em;padding-right:1em;padding-bottom:6em;padding-left:1em;background-color:#ffffff;color:#1f1f1f\"><div class=\"gb-layout-column-wrap gb-block-layout-column-gap-3 gb-is-responsive-column\" style=\"max-width:1200px\"><!-- wp:genesis-blocks/gb-column --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column\"><div class=\"gb-block-layout-column-inner\"><!-- wp:image {\"sizeSlug\":\"large\"} --> <figure class=\"wp-block-image size-large\"><img src=\"https://demo.studiopress.com/page-builder/slate/gb_slate_image_person.jpg\" alt=\"\"/></figure> <!-- /wp:image --></div></div> <!-- /wp:genesis-blocks/gb-column --> <!-- wp:genesis-blocks/gb-column {\"columnVerticalAlignment\":\"center\"} --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column gb-is-vertically-aligned-center\"><div class=\"gb-block-layout-column-inner\"><!-- wp:heading {\"className\":\"gpb-fluid-4\",\"style\":{\"typography\":{\"fontSize\":40},\"color\":{\"text\":\"#1f1f1f\"}}} --> <h2 class=\"gpb-fluid-4 has-text-color\" style=\"font-size:40px;color:#1f1f1f\">Our work ethic</h2> <!-- /wp:heading --> <!-- wp:paragraph {\"style\":{\"typography\":{\"fontSize\":22},\"color\":{\"text\":\"#1f1f1f\"}}} --> <p class=\"has-text-color\" style=\"font-size:22px;color:#1f1f1f\">We're here to help you navigate the increasingly complicated process of launching a website or native web app. </p> <!-- /wp:paragraph --> <!-- wp:separator {\"className\":\"is-style-default\"} --> <hr class=\"wp-block-separator is-style-default\"/> <!-- /wp:separator --> <!-- wp:paragraph --> <p><strong>Tell us your story</strong><br>Let's chat about what you're looking to build and see if our team is a good fit for the project.</p> <!-- /wp:paragraph --> <!-- wp:paragraph --> <p><strong>Define the scope</strong><br>We'll take a look at all the details of your project and discuss how to split up the work on our team.</p> <!-- /wp:paragraph --> <!-- wp:paragraph --> <p><strong>Start wireframes and code</strong><br>We'll work with you the entire way, from wireframes to walking you through live code previews.</p> <!-- /wp:paragraph --> <!-- wp:paragraph --> <p><strong>We live to launch products</strong><br>Launching products is our passion. We'll help you get your product live and help spread the word.</p> <!-- /wp:paragraph --></div></div> <!-- /wp:genesis-blocks/gb-column --></div></div> <!-- /wp:genesis-blocks/gb-columns --> <!-- wp:genesis-blocks/gb-columns {\"columns\":1,\"layout\":\"one-column\",\"align\":\"full\",\"paddingTop\":6,\"paddingRight\":1,\"paddingBottom\":6,\"paddingLeft\":1,\"paddingUnit\":\"em\",\"customTextColor\":\"#1f1f1f\",\"customBackgroundColor\":\"#ededed\",\"columnMaxWidth\":1200,\"className\":\"gb-slate-team gb-layout-team-1\"} --> <div class=\"wp-block-genesis-blocks-gb-columns gb-slate-team gb-layout-team-1 gb-layout-columns-1 one-column gb-has-custom-background-color gb-has-custom-text-color gb-columns-center alignfull\" style=\"padding-top:6em;padding-right:1em;padding-bottom:6em;padding-left:1em;background-color:#ededed;color:#1f1f1f\"><div class=\"gb-layout-column-wrap gb-block-layout-column-gap-2 gb-is-responsive-column\" style=\"max-width:1200px\"><!-- wp:genesis-blocks/gb-column --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column\"><div class=\"gb-block-layout-column-inner\"><!-- wp:genesis-blocks/gb-container {\"containerMarginBottom\":5,\"containerMaxWidth\":840} --> <div style=\"margin-bottom:5%\" class=\"wp-block-genesis-blocks-gb-container gb-block-container\"><div class=\"gb-container-inside\"><div class=\"gb-container-content\" style=\"max-width:840px\"><!-- wp:heading {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#1f1f1f\"},\"typography\":{\"fontSize\":40}}} --> <h2 class=\"has-text-align-center has-text-color\" style=\"font-size:40px;color:#1f1f1f\">Meet our amazing team.</h2> <!-- /wp:heading --> <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#1f1f1f\"}}} --> <p class=\"has-text-align-center has-text-color\" style=\"color:#1f1f1f\">We're a talented group of creative individuals interested in art, cinematography, design, music, and all niches in between. Get to know us and what we can do for you!</p> <!-- /wp:paragraph --></div></div></div> <!-- /wp:genesis-blocks/gb-container --> <!-- wp:genesis-blocks/gb-columns {\"columns\":3,\"layout\":\"gb-3-col-equal\"} --> <div class=\"wp-block-genesis-blocks-gb-columns gb-layout-columns-3 gb-3-col-equal\"><div class=\"gb-layout-column-wrap gb-block-layout-column-gap-2 gb-is-responsive-column\"><!-- wp:genesis-blocks/gb-column --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column\"><div class=\"gb-block-layout-column-inner\"><!-- wp:genesis-blocks/gb-profile-box {\"profileImgID\":10230,\"profileBackgroundColor\":\"#ededed\",\"profileTextColor\":\"#1f1f1f\"} --> <div style=\"background-color:#ededed;color:#1f1f1f\" class=\"wp-block-genesis-blocks-gb-profile-box square gb-has-avatar gb-font-size-18 gb-block-profile gb-profile-columns\"><div class=\"gb-profile-column gb-profile-avatar-wrap\"><div class=\"gb-profile-image-wrap\"><figure class=\"gb-profile-image-square\"><img class=\"gb-profile-avatar wp-image-10230\" src=\"https://demo.studiopress.com/page-builder/person-m-1.jpg\" alt=\"team member avatar\"/></figure></div></div><div class=\"gb-profile-column gb-profile-content-wrap\"><h2 class=\"gb-profile-name\" style=\"color:#1f1f1f\">Kyle Zion</h2><p class=\"gb-profile-title\" style=\"color:#1f1f1f\">Screenprinter</p><div class=\"gb-profile-text\"><p>Add biography text for your team member here. You can also remove this text if you'd rather just have a name and title.</p></div><ul class=\"gb-social-links\"></ul></div></div> <!-- /wp:genesis-blocks/gb-profile-box --></div></div> <!-- /wp:genesis-blocks/gb-column --> <!-- wp:genesis-blocks/gb-column --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column\"><div class=\"gb-block-layout-column-inner\"><!-- wp:genesis-blocks/gb-profile-box {\"profileImgID\":10211,\"profileBackgroundColor\":\"#ededed\",\"profileTextColor\":\"#1f1f1f\"} --> <div style=\"background-color:#ededed;color:#1f1f1f\" class=\"wp-block-genesis-blocks-gb-profile-box square gb-has-avatar gb-font-size-18 gb-block-profile gb-profile-columns\"><div class=\"gb-profile-column gb-profile-avatar-wrap\"><div class=\"gb-profile-image-wrap\"><figure class=\"gb-profile-image-square\"><img class=\"gb-profile-avatar wp-image-10211\" src=\"https://demo.studiopress.com/page-builder/person-w-3.jpg\" alt=\"avatar placeholder\"/></figure></div></div><div class=\"gb-profile-column gb-profile-content-wrap\"><h2 class=\"gb-profile-name\" style=\"color:#1f1f1f\">Fran Acadia</h2><p class=\"gb-profile-title\" style=\"color:#1f1f1f\">People Engineer</p><div class=\"gb-profile-text\"><p>Add biography text for your team member here. You can also remove this text if you'd rather just have a name and title.</p></div><ul class=\"gb-social-links\"></ul></div></div> <!-- /wp:genesis-blocks/gb-profile-box --></div></div> <!-- /wp:genesis-blocks/gb-column --> <!-- wp:genesis-blocks/gb-column --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column\"><div class=\"gb-block-layout-column-inner\"><!-- wp:genesis-blocks/gb-profile-box {\"profileImgID\":10225,\"profileBackgroundColor\":\"#ededed\",\"profileTextColor\":\"#1f1f1f\"} --> <div style=\"background-color:#ededed;color:#1f1f1f\" class=\"wp-block-genesis-blocks-gb-profile-box square gb-has-avatar gb-font-size-18 gb-block-profile gb-profile-columns\"><div class=\"gb-profile-column gb-profile-avatar-wrap\"><div class=\"gb-profile-image-wrap\"><figure class=\"gb-profile-image-square\"><img class=\"gb-profile-avatar wp-image-10225\" src=\"https://demo.studiopress.com/page-builder/person-m-3.jpg\" alt=\"team member avatar\"/></figure></div></div><div class=\"gb-profile-column gb-profile-content-wrap\"><h2 class=\"gb-profile-name\" style=\"color:#1f1f1f\">Giannis Teton</h2><p class=\"gb-profile-title\" style=\"color:#1f1f1f\">Office Manager</p><div class=\"gb-profile-text\"><p>Add biography text for your team member here. You can also remove this text if you'd rather just have a name and title.</p></div><ul class=\"gb-social-links\"></ul></div></div> <!-- /wp:genesis-blocks/gb-profile-box --></div></div> <!-- /wp:genesis-blocks/gb-column --></div></div> <!-- /wp:genesis-blocks/gb-columns --></div></div> <!-- /wp:genesis-blocks/gb-column --></div></div> <!-- /wp:genesis-blocks/gb-columns --> <!-- wp:genesis-blocks/gb-columns {\"columns\":2,\"layout\":\"gb-2-col-wideleft\",\"align\":\"full\",\"paddingTop\":6,\"paddingRight\":1,\"paddingBottom\":6,\"paddingLeft\":1,\"paddingUnit\":\"em\",\"customTextColor\":\"#f5f5f5\",\"customBackgroundColor\":\"#0073e5\",\"columnMaxWidth\":1200,\"className\":\"gpb-slate-section-cta-accent\"} --> <div class=\"wp-block-genesis-blocks-gb-columns gpb-slate-section-cta-accent gb-layout-columns-2 gb-2-col-wideleft gb-has-custom-background-color gb-has-custom-text-color gb-columns-center alignfull\" style=\"padding-top:6em;padding-right:1em;padding-bottom:6em;padding-left:1em;background-color:#0073e5;color:#f5f5f5\"><div class=\"gb-layout-column-wrap gb-block-layout-column-gap-2 gb-is-responsive-column\" style=\"max-width:1200px\"><!-- wp:genesis-blocks/gb-column {\"textAlign\":\"left\",\"paddingUnit\":\"em\",\"columnVerticalAlignment\":\"center\"} --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column gb-is-vertically-aligned-center\"><div class=\"gb-block-layout-column-inner\" style=\"text-align:left\"><!-- wp:heading {\"style\":{\"typography\":{\"fontSize\":40},\"color\":{\"text\":\"#f5f5f5\"}}} --> <h2 class=\"has-text-color\" style=\"font-size:40px;color:#f5f5f5\">Get a project quote today!</h2> <!-- /wp:heading --> <!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#f5f5f5\"}}} --> <p class=\"has-text-color\" style=\"color:#f5f5f5\">We'll put together a customized quote about your project and work with you to get started on your project. Let's build something together!</p> <!-- /wp:paragraph --> <!-- wp:buttons --> <div class=\"wp-block-buttons\"><!-- wp:button {\"borderRadius\":4,\"style\":{\"color\":{\"text\":\"#0073e5\",\"background\":\"#ffffff\"}},\"className\":\"is-style-fill\"} --> <div class=\"wp-block-button is-style-fill\"><a class=\"wp-block-button__link has-text-color has-background\" style=\"border-radius:4px;background-color:#ffffff;color:#0073e5\"><strong>Get in touch today!</strong></a></div> <!-- /wp:button --></div> <!-- /wp:buttons --></div></div> <!-- /wp:genesis-blocks/gb-column --> <!-- wp:genesis-blocks/gb-column {\"textAlign\":\"right\",\"paddingUnit\":\"em\",\"columnVerticalAlignment\":\"center\"} --> <div class=\"wp-block-genesis-blocks-gb-column gb-block-layout-column gb-is-vertically-aligned-center\"><div class=\"gb-block-layout-column-inner\" style=\"text-align:right\"></div></div> <!-- /wp:genesis-blocks/gb-column --></div></div> <!-- /wp:genesis-blocks/gb-columns -->",
	'name'       => esc_html__( 'Slate About', 'genesis-blocks' ),
	'category'   => [
		esc_html__( 'team', 'genesis-blocks' ),
		esc_html__( 'services', 'genesis-blocks' ),
		esc_html__( 'landing', 'genesis-blocks' ),
		esc_html__( 'media', 'genesis-blocks' ),
	],
	'keywords'   => [
		esc_html__( 'business', 'genesis-blocks' ),
		esc_html__( 'landing', 'genesis-blocks' ),
		esc_html__( 'about', 'genesis-blocks' ),
		esc_html__( 'team', 'genesis-blocks' ),
		esc_html__( 'slate', 'genesis-blocks' ),
		esc_html__( 'slate about', 'genesis-blocks' ),
	],
	'image'      => 'https://demo.studiopress.com/page-builder/slate/gb_slate_layout_about.jpg',
];
