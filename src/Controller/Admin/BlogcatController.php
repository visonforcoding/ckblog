<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Blogcat Controller
 *
 * @property \App\Model\Table\BlogcatTable $Blogcat
 */
class BlogcatController extends AppController
{

/**
* Index method
*
* @return void
*/
public function index()
{
$this->set('blogcat', $this->Blogcat);
}

    /**
     * View method
     *
     * @param string|null $id Blogcat id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->autoLayout(false);
        $blogcat = $this->Blogcat->get($id, [
            'contain' => []
        ]);
        $this->set('blogcat', $blogcat);
        $this->set('_serialize', ['blogcat']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $blogcat = $this->Blogcat->newEntity();
        if ($this->request->is('post')) {
            $blogcat = $this->Blogcat->patchEntity($blogcat, $this->request->data);
            if ($this->Blogcat->save($blogcat)) {
                 $this->Util->ajaxReturn(true,'添加成功');
            } else {
                 $errors = $blogcat->errors();
                 $this->Util->ajaxReturn(['status'=>false, 'msg'=>getMessage($errors),'errors'=>$errors]);
            }
        }
                $this->set(compact('blogcat'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Blogcat id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
         $blogcat = $this->Blogcat->get($id,[
            'contain' => []
        ]);
        if ($this->request->is(['post','put'])) {
            $blogcat = $this->Blogcat->patchEntity($blogcat, $this->request->data);
            if ($this->Blogcat->save($blogcat)) {
                  $this->Util->ajaxReturn(true,'修改成功');
            } else {
                 $errors = $blogcat->errors();
               $this->Util->ajaxReturn(false,getMessage($errors));
            }
        }
                  $this->set(compact('blogcat'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Blogcat id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod('post');
         $id = $this->request->data('id');
                if ($this->request->is('post')) {
                $blogcat = $this->Blogcat->get($id);
                 if ($this->Blogcat->delete($blogcat)) {
                     $this->Util->ajaxReturn(true,'删除成功');
                } else {
                    $errors = $blogcat->errors();
                    $this->Util->ajaxReturn(true,getMessage($errors));
                }
          }
    }

/**
* get jqgrid data 
*
* @return json
*/
public function getDataList()
{
        $this->request->allowMethod('ajax');
        $page = $this->request->data('page');
        $rows = $this->request->data('rows');
        $sort = 'Blogcat.'.$this->request->data('sidx');
        $order = $this->request->data('sord');
        $keywords = $this->request->data('keywords');
        $begin_time = $this->request->data('begin_time');
        $end_time = $this->request->data('end_time');
        $where = [];
        if (!empty($keywords)) {
            $where[' username like'] = "%$keywords%";
        }
        if (!empty($begin_time) && !empty($end_time)) {
            $begin_time = date('Y-m-d', strtotime($begin_time));
            $end_time = date('Y-m-d', strtotime($end_time));
            $where['and'] = [['date(`ctime`) >' => $begin_time], ['date(`ctime`) <' => $end_time]];
        }
                $data = $this->getJsonForJqrid($page, $rows, '', $sort, $order,$where);
                $this->autoRender = false;
        $this->response->type('json');
        echo json_encode($data);
}

/**
* export csv
*
* @return csv 
*/
public function exportExcel()
{
        $sort = $this->request->data('sidx');
        $order = $this->request->data('sord');
        $keywords = $this->request->data('keywords');
        $begin_time = $this->request->data('begin_time');
        $end_time = $this->request->data('end_time');
        $where = [];
        if (!empty($keywords)) {
            $where[' username like'] = "%$keywords%";
        }
        if (!empty($begin_time) && !empty($end_time)) {
            $begin_time = date('Y-m-d', strtotime($begin_time));
            $end_time = date('Y-m-d', strtotime($end_time));
            $where['and'] = [['date(`ctime`) >' => $begin_time], ['date(`ctime`) <' => $end_time]];
        }
        $Table =  $this->Blogcat;
        $column = ['父id','类别名','同级排名','级别','创建时教'];
        $query = $Table->find();
        $query->hydrate(false);
        $query->select(['pid','name','rank','depth','ctime']);
         if (!empty($where)) {
            $query->where($where);
        }
        if (!empty($sort) && !empty($order)) {
            $query->order([$sort => $order]);
        }
        $res = $query->toArray();
        $this->autoRender = false;
        $filename = 'Blogcat_'.date('Y-m-d').'.csv';
        \Wpadmin\Utils\Export::exportCsv($column,$res,$filename);

}
}
