<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 19.08.14
 * Time: 09:25
 */

namespace Homepage;


class Footnote extends \Homepage\Model {
    protected $_table = "homepage_footnote";
    /**
     * @column
     * @type text
     * @label title
     * @validate required
     */
    protected $_title;
    /**
     * @column
     * @type text
     * @label link
     * @validate required
     */
    protected $_link;
    /**
     * @column
     * @type text
     * @label description
     * @validate required
     */
    protected $_description;
}