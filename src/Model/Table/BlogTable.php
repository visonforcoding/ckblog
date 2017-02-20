<?php

namespace App\Model\Table;

use App\Model\Entity\Blog;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Blog Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Blogcat
 * @property \Cake\ORM\Association\BelongsTo $User
 */
class BlogTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('cwp_blog');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('Blogcat', [
            'className' => 'Blogcat',
            'foreignKey' => 'category_id'
        ]);
        $this->belongsTo('Admin', [
            'className' => 'Admin',
            'foreignKey' => 'admin_id'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'ctime' => 'new',
                    'updatetime' => 'always'
                ]
            ]
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->requirePresence('title', 'create')
                ->notEmpty('title');

        $validator
                ->requirePresence('guide', 'create')
                ->notEmpty('guide');

        $validator
                ->requirePresence('cover', 'create')
                ->notEmpty('cover');

        $validator
                ->requirePresence('content', 'create')
                ->notEmpty('content');

        $validator
                ->requirePresence('keywords', 'create')
                ->notEmpty('keywords');

        $validator
                ->requirePresence('description', 'create')
                ->notEmpty('description');

        $validator
                ->integer('hits')
                ->allowEmpty('hits');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['category_id'], 'Blogcat'));
        $rules->add($rules->existsIn(['admin_id'], 'Admin'));
        return $rules;
    }

}
