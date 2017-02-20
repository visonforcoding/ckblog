<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Joke Controller
 *
 * @property \App\Model\Table\JokeTable $Joke
 */
class JokeController extends AppController
{

/**
* Index method
*
* @return void
*/
public function index()
{
$this->set('joke', $this->Joke);
}

    /**
     * View method
     *
     * @param string|null $id Joke id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->autoLayout(false);
        $joke = $this->Joke->get($id, [
            'contain' => []
        ]);
        $this->set('joke', $joke);
        $this->set('_serialize', ['joke']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $joke = $this->Joke->newEntity();
        if ($this->request->is('post')) {
            $joke = $this->Joke->patchEntity($joke, $this->request->data);
            if ($this->Joke->save($joke)) {
                 $this->Util->ajaxReturn(true,'添加成功');
            } else {
                 $errors = $joke->errors();
                 $this->Util->ajaxReturn(['status'=>false, 'msg'=>getMessage($errors),'errors'=>$errors]);
            }
        }
                $this->set(compact('joke'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Joke id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
         $joke = $this->Joke->get($id,[
            'contain' => []
        ]);
        if ($this->request->is(['post','put'])) {
            $joke = $this->Joke->patchEntity($joke, $this->request->data);
            if ($this->Joke->save($joke)) {
                  $this->Util->ajaxReturn(true,'修改成功');
            } else {
                 $errors = $joke->errors();
               $this->Util->ajaxReturn(false,getMessage($errors));
            }
        }
                  $this->set(compact('joke'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Joke id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod('post');
         $id = $this->request->data('id');
                if ($this->request->is('post')) {
                $joke = $this->Joke->get($id);
                 if ($this->Joke->delete($joke)) {
                     $this->Util->ajaxReturn(true,'删除成功');
                } else {
                    $errors = $joke->errors();
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
        $sort = 'Joke.'.$this->request->data('sidx');
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
        $Table =  $this->Joke;
        $column = ['hash','url','标题','图片','创建时间'];
        $query = $Table->find();
        $query->hydrate(false);
        $query->select(['hash','url','title','img','create_time']);
         if (!empty($where)) {
            $query->where($where);
        }
        if (!empty($sort) && !empty($order)) {
            $query->order([$sort => $order]);
        }
        $res = $query->toArray();
        $this->autoRender = false;
        $filename = 'Joke_'.date('Y-m-d').'.csv';
        \Wpadmin\Utils\Export::exportCsv($column,$res,$filename);

}
}
