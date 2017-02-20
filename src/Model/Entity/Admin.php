<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Admin Entity.
 *
 * @property int $id
 * @property string $username
 * @property string $username_canonical
 * @property string $email
 * @property string $email_canonical
 * @property bool $enabled
 * @property string $salt
 * @property string $password
 * @property \Cake\I18n\Time $last_login
 * @property bool $locked
 * @property bool $expired
 * @property \Cake\I18n\Time $expires_at
 * @property string $confirmation_token
 * @property \Cake\I18n\Time $password_requested_at
 * @property string $roles
 * @property bool $credentials_expired
 * @property \Cake\I18n\Time $credentials_expire_at
 */
class Admin extends Entity
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

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
