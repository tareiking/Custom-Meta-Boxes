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

		$required_attr = $field->has_validation_rule( 'required' );

		$this->assertTrue( $required_attr );

	}

	function testFieldHasValidation() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1, 2 ), array( 'validation' => array( 'required' ) ) );

		$this->assertTrue( $field->requires_validation() );

	}

	function testFieldHasRequiredValidationAttr() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1 ), array( 'validation' => array( 'required' => true ) ) );

		$validation_attr = $this->getValidationAttributeOutput( $field );

		$this->assertContains( 'data-parsley-required' , $validation_attr );
	}

	function testFieldHasEmailValidationAttr() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1 ), array( 'validation' => array( 'required' => true, 'email' => true ) ) );

		$validation_attr = $this->getValidationAttributeOutput( $field );

		$this->assertContains( 'data-parsley-type="email"', $validation_attr );
	}

	function testFieldHasMinLengthValidationAttr() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1 ), array( 'validation' => array( 'minlength' => 1 ) ) );

		$validation_attr = $this->getValidationAttributeOutput( $field );

		$this->assertContains( 'data-parsley-minlength="1"', $validation_attr );
	}

	function testFieldHasMaxLengthValidationAttr() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1 ), array( 'validation' => array( 'maxlength' => 1 ) ) );

		$validation_attr = $this->getValidationAttributeOutput( $field );

		$this->assertContains( 'data-parsley-maxlength="1"', $validation_attr );
	}

	function testFieldHasMaxLengthAndMaxLengthValidationAttr() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1 ), array( 'validation' => array( 'maxlength' => 1, 'minlength' => 1 ) ) );

		$validation_attr = $this->getValidationAttributeOutput( $field );

		$this->assertContains( 'data-parsley-maxlength="1"  data-parsley-minlength="1"', $validation_attr );
	}

	function testFieldHasMinValidationAttr() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1 ), array( 'validation' => array( 'min' => 1 ) ) );

		$validation_attr = $this->getValidationAttributeOutput( $field );

		$this->assertContains( 'data-parsley-min="1"', $validation_attr );
	}

	function testFieldHasMaxValidationAttr() {

		$field = new CMB_Text_Field( 'foo', 'Text', array( 1 ), array( 'validation' => array( 'max' => 1 ) ) );

		$validation_attr = $this->getValidationAttributeOutput( $field );

		$this->assertContains( 'data-parsley-max="1"', $validation_attr );
	}

	/**
	 * @param $field CMB_Field
	 *
	 * @return string html attribute string used for validation of the passed field.
	 */
	function getValidationAttributeOutput( $field ) {

		ob_start();
		$field->validation_attr();
		$attr_output = ob_get_contents();
		ob_end_clean();

		return $attr_output;
	}
}
