<?php
namespace Nixhatter\ICMS\model;
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/3/2016
 * Time: 12:30 PM
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        define('_ICMS', 1);
    }

    public function testTruncate() {

        $model = new Model();
        $string = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tempus ultrices sollicitudin. Nam consectetur luctus mi, eget lacinia mi fermentum a.
        Donec eu auctor nisl, quis ultrices elit. Integer arcu est, lacinia vel aliquam vel, pulvinar et augue. Cras pharetra congue orci, vitae venenatis magna ornare non.
        Aenean ultricies diam sit amet nisi tincidunt, in viverra lectus viverra. In justo urna, porttitor sit amet eros interdum, sollicitudin commodo nisi. Aenean odio leo,
        sodales placerat finibus ac, ultricies sit amet ipsum. Aenean et dui ut lorem consectetur molestie.
        Aliquam in suscipit elit. Maecenas sed euismod lectus. Suspendisse condimentum dui eget enim rhoncus volutpat. Pellentesque ac pharetra urna.
        Mauris tristique, nulla scelerisque ornare pretium, urna augue auctor neque, sit amet rhoncus nisl orci et nisl. Mauris sit amet sodales justo. Nulla eleifend magna
        a imperdiet bibendum. Ut viverra ut libero vitae cursus. Fusce feugiat tellus ipsum, vel efficitur leo rutrum non. Sed nec ultrices justo. Maecenas convallis libero massa, ut eleifend lacus sodales ut.
        Nunc venenatis volutpat justo eget sagittis. Vivamus porta sagittis ante et semper. Cras eleifend, mauris elementum sagittis consectetur, metus sem tempus enim,
        non sodales diam lorem nec purus. Nunc suscipit ut magna in eleifend. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
        Maecenas auctor interdum ipsum, nec egestas dolor blandit in. Vestibulum suscipit finibus risus. Fusce risus leo, luctus ac malesuada at, scelerisque non tortor.
        Quisque ut diam sit amet ipsum hendrerit fermentum. Quisque ut finibus neque, ut iaculis arcu. ";

        $truncated = $model->truncate($string);

        $this->assertLessThan(320, strlen ($truncated));

    }
}
