<?php defined('SYSPATH') or die('No direct access allowed!');

 require_once Kohana::find_file('classes', 'ORM');
class SampleTest extends Unittest_TestCase
{
    /**
    *
    * @group somegroup
    */
    public function testAdd()
    {
//        $post = []; //'username' => 'John' 
// 
//        $username = Arr::get($post, 'username');
// 
//        $this->assertEquals($username, null);
        
        
        // Create a stub for the SomeClass class.
//        $stub = $this->getMock( 'Model_Page' );
//
//        // Configure the stub.
//        $stub->expects($this->once())
//                ->method( 'listPages' )
////                ->with()
//                ->will( $this->returnValue(['index']) );
//        $stub->render();
        // Calling $stub->doSomething() will now return
        // 'foo'.
        $page = ORM::factory('Page');
        $this->assertContains( 'Консультанту', $page->listPages() );
        
    }
}