<?php

namespace App\Controller\Home;

use App\Controller\Home\AppController;

/**
 * Index Controller
 *  博客首页
 * @property \App\Model\Table\IndexTable $Index
 * @property \App\Model\Table\BlogTable $Blog 博客表
 */
class IndexController extends AppController {

    /**
     * 分页的每页的页数
     * @var type 
     */
    protected $listnum = 10;
    
    public function initialize() {
        parent::initialize();
        $this->loadModel('Blog');
    }

    /**
     * Index method
     * 
     *
     * @return \Cake\Network\Response|null
     */
    public function index($id=null) {
        //首页的文章列表 第一页
        $query = $this->Blog->find();
        $query->contain(['Admin']);
        $navLink = '/index/load-more/2.json';
        if(!empty($id)&&  is_numeric($id)){
            $query->where(['category_id'=>$id]);
            $navLink = '/index/load-more/2.json?list='.$id;
        }
        $blogs = $query->orderDesc('ctime')
                ->limit($this->listnum)
                ->formatResults(function($items){
                    //时间语义化转换
                    return $items->map(function($item){
                                         //时间语义化转换
                        $item['ctime_str'] =$item['ctime']->timeAgoInWords(
                           [ 'accuracy' => [
                                     'year' => 'year',
                                     'month' => 'month',
                                     'week' => 'week',
                                     'day'=>'day',
                                     'hour' => 'hour'
                                 ],'end' => '+10 year']
                        );
                     return $item;
                   });
                })
                ->toArray();
        //按月份
        $archives = $this->Blog->find()->hydrate(false)->select(['cyear'=>'year(ctime)','cmonth'=>'monthname(ctime)'])
                ->group(['year(ctime)','monthname(ctime)'])
                ->order(['year(ctime)'=>'desc','monthname(ctime)'=>'desc'])
                ->toArray();
        
        //按类别
        $blogCats = $this->Blog->find()->select(['counts'=>'count(Blog.id)','catid'=>'bc.id','name'=>'bc.name'])
                ->join([
                    'table'=>'cwp_blogcat',
                    'alias'=>'bc',
                    'type'=>'right              ',
                    'conditions'=>'bc.id = Blog.category_id',
                ])->group(['bc.id'])->execute()->fetchAll('assoc');
        //按点击量
        $rankBlogs = $this->Blog->find()->orderDesc('hits')->limit(5)->toArray();
        $this->set([
            'pageTitle'=>'首页',
            'blogs'=>$blogs,
            'archives'=>$archives,
            'blogCats'=>$blogCats,
            'navLink'=>$navLink,
            'rankBlogs'=>$rankBlogs
        ]);
        $this->set('_serialize', ['blogs']);

    }
    
    
    public function loadMore($page){
                //首页的文章列表 第一页
        $query = $this->Blog->find();
        $query->contain(['Admin']);
        if($this->request->query('list')){
            $query->where(['category_id'=>  $this->request->query('list')]);
        }        
         $blogs =  $query ->orderDesc('ctime')
                ->page($page,  $this->listnum)
                ->formatResults(function($items){
                    //时间语义化转换
                    return $items->map(function($item){
                                         //时间语义化转换
                        $item['ctime_str'] =$item['ctime']->timeAgoInWords(
                           [ 'accuracy' => [
                                     'year' => 'year',
                                     'month' => 'month',
                                     'week' => 'week',
                                     'day'=>'day',
                                     'hour' => 'hour'
                                 ],'end' => '+10 year']
                        );
                     return $item;
                   });
                })
                ->toArray();
        $this->set(compact('blogs'));
        $this->set('_serialize', ['blogs']);
           
    }

    /**
     * View method
     *
     * @param string|null $id Index id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function blog($id = null) {
        $blog = $this->Blog->get($id, [
            'contain' => ['Admin']
        ]);
        $blog->hits +=1;
        $this->Blog->save($blog);
        $this->viewBuilder()->template('details');
        $this->set('blog', $blog);
        $this->set('pageTitle', $blog->title);
        $this->set('_serialize', ['blog']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $index = $this->Index->newEntity();
        if ($this->request->is('post')) {
            $index = $this->Index->patchEntity($index, $this->request->data);
            if ($this->Index->save($index)) {
                $this->Flash->success(__('The index has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The index could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('index'));
        $this->set('_serialize', ['index']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Index id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $index = $this->Index->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $index = $this->Index->patchEntity($index, $this->request->data);
            if ($this->Index->save($index)) {
                $this->Flash->success(__('The index has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The index could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('index'));
        $this->set('_serialize', ['index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Index id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $index = $this->Index->get($id);
        if ($this->Index->delete($index)) {
            $this->Flash->success(__('The index has been deleted.'));
        } else {
            $this->Flash->error(__('The index could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

}
