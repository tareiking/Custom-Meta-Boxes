<?php

class FieldValidationTestCase extends WP_UnitTestCase {

	private $post;

	function setUp() {

		parent::setUp();

		$args = array(
			'post_author'  => 1,
			'post_status'  => 'publish',
			'post_content' => rand_str(),
			'post_title'   => rand_str(),
			'post_type'    => 'post',
		);

		$id = wp_insert_post( $args );

		$this->post = get_post( $id );

	}

	function tearDown() {
		wp_delete_post( $this->post->ID, true );
		unset( $this->post );
		parent::tearDown();
	}

	function testFieldContainsValidationRule() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1, 2 ), array( 'validation' => array( 'required' ) ) );

		// Required attr set
		$required_attr = $field->has_validation_rule( 'required' );

		$this->assertTrue( $required_attr );

	}

	function testFieldHasValidation() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1, 2 ), array( 'validation' => array( 'required' ) ) );

		$this->assertTrue( $field->requires_validation() );

	}

	function testFieldHasRequiredAttr() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1 ), array( 'validation' => array( 'required' ) ) );

		ob_start();
		$required_attr = $field->validation_attr();
		$required_attr = ob_get_contents();
		ob_end_clean();

		$this->assertSame( $required_attr, ' required ' );
	}

	function testParsleyIsLoaded() {}

}
