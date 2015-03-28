<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Brand.php";
    require_once "src/Store.php";


    $DB = new PDO('pgsql:host=localhost;dbname=shoes_test;user=brian;password=1234');


    class BrandTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Brand::deleteAll();
            Store::deleteAll();
        }

        function testGetStyle()
        {
            //Arrange
            $style = "Nike";
            $test_brand = new Brand($style);
            //Act
            $result = $test_brand->getStyle();
            //Assert
            $this->assertEquals($style, $result);
        }
        function testGetId()
        {
            //Arrange
            $style = "Nike";
            $id = 1;
            $test_brand = new Brand($style, $id);
            //Act
            $result = $test_brand->getId();
            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testSetStyle()
        {
            //Arrange
            $style = "Nike";
            $id = 1;
            $test_brand = new Brand($style, $id);
            //Act
            $test_brand->setStyle("Kicks");
            //Assert
            $result = $test_brand->getStyle();
            $this->assertEquals("Kicks", $result);
        }

        function testSetId()
        {
            //Arrange
            $style = "Nike";
            $id = 1;
            $test_brand = new Brand($style, $id);
            //Act
            $test_brand->setId(1234);

            //Assert
            $result = $test_brand->getId();
            $this->assertEquals(1234, $result);
        }

        function testSave()
        {
            $style = "Nike";
            $id = 1;
            $test_brand = new Brand($style, $id);
            $test_brand->save();

            //Act
            $result = Brand::getAll();

            //Assert
            $this->assertEquals($test_brand, $result[0]);

        }

        function testGetAll()
        {
            $style = "Nike";
            $id = 1;
            $test_brand = new Brand($style, $id);
            $test_brand->save();

            $style2 = "Adidas";
            $id2 = 3;
            $test_brand2 = new Brand($style2, $id2);
            $test_brand2->save();

            //Act
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([$test_brand, $test_brand2], $result);
        }

        function testDeleteAll()
        {
            $style = "Nike";
            $id = 1;
            $test_brand = new Brand($style, $id);
            $test_brand->save();

            $style2 = "Adidas";
            $id2 = 3;
            $test_brand2 = new Brand($style2, $id2);
            $test_brand2->save();

            //Act
            Brand::deleteAll();

            //Assert
            $result = Brand::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            $style = "Nike";
            $id = 1;
            $test_brand = new Brand($style, $id);
            $test_brand->save();

            $style2 = "Adidas";
            $id2 = 3;
            $test_brand2 = new Brand($style2, $id2);
            $test_brand2->save();

            $result = Brand::find($test_brand2->getId());

            $this->assertEquals($test_brand2, $result);
        }

        function testUpdate()
        {
            $style = "Nike";
            $id = 1;
            $test_brand = new Brand($style, $id);
            $test_brand->save();

            $new_brand = "Mercury";

            $test_brand->update($new_brand);

            $result = $test_brand->getStyle();
            $this->assertEquals($new_brand, $result);

        }

    }
?>
