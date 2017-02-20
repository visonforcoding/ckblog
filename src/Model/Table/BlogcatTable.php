<?php
namespace App\Model\Table;

use App\Model\Entity\Blogcat;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Blogcat Model
 *
 */
class BlogcatTable extends Table
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

        $this->table('cwp_blogcat');
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
            ->integer('pid')
            ->requirePresence('pid', 'create')
            ->notEmpty('pid');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('rank')
            ->allowEmpty('rank');

        $validator
            ->integer('depth')
            ->allowEmpty('depth');

        $validator
            ->dateTime('ctime')
            ->allowEmpty('ctime');

        return $validator;
    }
}
