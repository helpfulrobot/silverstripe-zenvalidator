<?php

class ZenValidatorConstraintTest extends SapphireTest{

	private function Form(){
		$fields 	= FieldList::create(TextField::create('Title'), TextField::create('Subtitle'));
		$actions 	= FieldList::create(FormAction::create('submit', 'submit'));
		$validator 	= ZenValidator::create();
		
		return Form::create(Controller::curr(), 'Form', $fields, $actions, $validator);
	}

	
	public function testRequired(){
		$zv = $this->Form()->getValidator();
		$zv->addRequiredFields(array('Title'));
		
		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_required')->getField();
		$this->assertTrue($field->getAttribute('data-required') == 'true');

		// test valid
		$data['Title'] = 'Some Text';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = '';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testMinLength(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_length::create('min', 5));
		
		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_length')->getField();
		$this->assertTrue($field->getAttribute('data-minlength') == '5');

		// test valid
		$data['Title'] = 'Some Text';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = 'Some';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testMaxLength(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_length::create('max', 5));
		
		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_length')->getField();
		$this->assertTrue($field->getAttribute('data-maxlength') == '5');

		// test valid
		$data['Title'] = 'Some';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = 'Some Text';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testRangeLength(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_length::create('range', 5, 10));
		
		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_length')->getField();
		$this->assertTrue($field->getAttribute('data-rangelength') == '[5,10]');

		// test valid
		$data['Title'] = 'Some Text';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = 'Some';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testMinValue(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_value::create('min', 5));
		
		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_value')->getField();
		$this->assertTrue($field->getAttribute('data-min') == '5');

		// test valid
		$data['Title'] = '6';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = '3';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testMaxValue(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_value::create('max', 5));
		
		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_value')->getField();
		$this->assertTrue($field->getAttribute('data-max') == '5');

		// test valid
		$data['Title'] = '3';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = '6';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testRangeValue(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_value::create('range', 5, 10));
		
		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_value')->getField();
		$this->assertTrue($field->getAttribute('data-range') == '[5,10]');

		// test valid
		$data['Title'] = '6';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = '3';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testRegex(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_regex::create("/^#(?:[0-9a-fA-F]{3}){1,2}$/"));
		
		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_regex')->getField();
		$this->assertTrue($field->getAttribute('data-regexp') == "^#(?:[0-9a-fA-F]{3}){1,2}$");

		// test valid
		$data['Title'] = '#ff0000';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = 'ff0000';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testURLType(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_type::create('url'));

		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_type')->getField();
		$this->assertTrue($field->getAttribute('data-type') == 'urlstrict');

		// test valid
		$data['Title'] = 'http://www.things.com';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = 'www.things.com';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testEmailType(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_type::create('email'));

		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_type')->getField();
		$this->assertTrue($field->getAttribute('data-type') == 'email');

		// test valid
		$data['Title'] = 'person@things.com';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = 'person@things';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testNumberType(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_type::create('number'));

		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_type')->getField();
		$this->assertTrue($field->getAttribute('data-type') == 'number');

		// test valid
		$data['Title'] = '500';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = '$500';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testAlphanumType(){
		$zv = $this->Form()->getValidator();
		$zv->setConstraint('Title', Constraint_type::create('alphanum'));

		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_type')->getField();
		$this->assertTrue($field->getAttribute('data-type') == 'alphanum');

		// test valid
		$data['Title'] = '500tests';
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = '500 tests + other things';
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testEqualto(){
		$form = $this->Form();
		$zv = $form->getValidator();
		$zv->setConstraint('Title', Constraint_equalto::create('Subtitle'));

		// test attributes
		$field = $zv->getConstraint('Title', 'Constraint_equalto')->getField();
		$this->assertTrue($field->getAttribute('data-equalTo') == 'Form_Form_Subtitle');

		// test valid
		$data['Title'] = '500tests';
		$data['Subtitle'] = '500tests';
		$form->loadDataFrom($data);
		$zv->php($data);
		$this->assertEmpty($zv->getErrors());

		// test invalid
		$data['Title'] = '500tests';
		$data['Subtitle'] = '600tests';
		$form->loadDataFrom($data);
		$zv->php($data);
		$errors = $zv->getErrors();
		$this->assertTrue($errors[0]['fieldName'] == 'Title');
	}


	public function testRemote(){
		// TODO
		// test remote CURL
		// test remote local
		// test get/post options
	}

}