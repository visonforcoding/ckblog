<?php
namespace App\Model\Table;

use App\Model\Entity\Admin;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Admin Model
 *
 */
class AdminTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('cwp_admin');
        $this->displayField('id');
        $this->primaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->requirePresence('username_canonical', 'create')
            ->notEmpty('username_canonical')
            ->add('username_canonical', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->requirePresence('email_canonical', 'create')
            ->notEmpty('email_canonical')
            ->add('email_canonical', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->boolean('enabled')
            ->requirePresence('enabled', 'create')
            ->notEmpty('enabled');

        $validator
            ->requirePresence('salt', 'create')
            ->notEmpty('salt');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->dateTime('last_login')
            ->allowEmpty('last_login');

        $validator
            ->boolean('locked')
            ->requirePresence('locked', 'create')
            ->notEmpty('locked');

        $validator
            ->boolean('expired')
            ->requirePresence('expired', 'create')
            ->notEmpty('expired');

        $validator
            ->dateTime('expires_at')
            ->allowEmpty('expires_at');

        $validator
            ->allowEmpty('confirmation_token');

        $validator
            ->dateTime('password_requested_at')
            ->allowEmpty('password_requested_at');

        $validator
            ->requirePresence('roles', 'create')
            ->notEmpty('roles');

        $validator
            ->boolean('credentials_expired')
            ->requirePresence('credentials_expired', 'create')
            ->notEmpty('credentials_expired');

        $validator
            ->dateTime('credentials_expire_at')
            ->allowEmpty('credentials_expire_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['username_canonical']));
        $rules->add($rules->isUnique(['email_canonical']));
        return $rules;
    }
}
