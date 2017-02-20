<?php
namespace App\Model\Table;

use App\Model\Entity\Menu;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Menu Model
 *
 */
class MenuTable extends Table
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

        $this->table('cwp_menu');
        $this->displayField('name');
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
            ->allowEmpty('name');

        $validator
            ->allowEmpty('node');

        $validator
            ->integer('pid')
            ->requirePresence('pid', 'create')
            ->notEmpty('pid');

        $validator
            ->allowEmpty('class');

        $validator
            ->integer('rank')
            ->allowEmpty('rank');

        $validator
            ->boolean('is_menu')
            ->requirePresence('is_menu', 'create')
            ->notEmpty('is_menu');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->allowEmpty('remark');

        return $validator;
    }
}
