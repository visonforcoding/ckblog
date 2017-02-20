<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Blog Entity.
 *
 * @property int $id
 * @property int $category_id
 * @property \App\Model\Entity\CwpBlogcat $cwp_blogcat
 * @property int $user_id
 * @property \App\Model\Entity\CwpUser $cwp_user
 * @property string $title
 * @property string $guide
 * @property string $cover
 * @property string $content
 * @property string $keywords
 * @property string $description
 * @property \Cake\I18n\Time $ctime
 * @property \Cake\I18n\Time $updatetime
 * @property int $hits
 */
class Blog extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
