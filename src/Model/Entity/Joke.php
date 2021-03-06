<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Joke Entity.
 *
 * @property int $id
 * @property string $hash
 * @property string $url
 * @property string $title
 * @property string $img
 * @property \Cake\I18n\Time $create_time
 */
class Joke extends Entity
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
