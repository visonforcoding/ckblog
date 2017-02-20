<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Blog Controller
 *
 * @property \App\Model\Table\BlogTable $Blog
 */
class BlogController extends AppController {

    /**
     * Index method
     *
     * @return void
     */
    public function index() {
        $this->set('blog', $this->Blog);
    }

    /**
     * View method
     *
     * @param string|null $id Blog id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null) {
        $this->viewBuilder()->autoLayout(false);
        $blog = $this->Blog->get($id, [
            'contain' => ['Blogcat', 'Admin']
        ]);
        $this->set('blog', $blog);
        $this->set('_serialize', ['blog']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $blog = $this->Blog->newEntity();
        if ($this->request->is('post')) {
            $blog->admin_id = $this->_user->id;
            $blog = $this->Blog->patchEntity($blog, $this->request->data);
            if ($this->Blog->save($blog)) {
                $this->Util->ajaxReturn(true, '添加成功');
            } else {
                $errors = $blog->errors();
                $this->Util->ajaxReturn(['status' => false, 'msg' => getMessage($errors), 'errors' => $errors]);
            }
        }
        $blogcat = $this->Blog->Blogcat->find('list', ['limit' => 200]);
        $admin = $this->Blog->Admin->find('list', ['limit' => 200]);
        $this->set(compact('blog', 'blogcat', 'admin'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Blog id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $blog = $this->Blog->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['post', 'put'])) {
            $blog = $this->Blog->patchEntity($blog, $this->request->data);
            if ($this->Blog->save($blog)) {
                $this->Util->ajaxReturn(true, '修改成功');
            } else {
                $errors = $blog->errors();
                $this->Util->ajaxReturn(false, getMessage($errors));
            }
        }
        $blogcat = $this->Blog->Blogcat->find('list', ['limit' => 200]);
        $admin = $this->Blog->Admin->find('list', ['limit' => 200]);
        $this->set(compact('blog', 'blogcat', 'admin'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod('post');
        $id = $this->request->data('id');
        if ($this->request->is('post')) {
            $blog = $this->Blog->get($id);
            if ($this->Blog->delete($blog)) {
                $this->Util->ajaxReturn(true, '删除成功');
            } else {
                $errors = $blog->errors();
                $this->Util->ajaxReturn(true, getMessage($errors));
            }
        }
    }

    /**
     * get jqgrid data 
     *
     * @return json
     */
    public function getDataList() {
        $this->request->allowMethod('ajax');
        $page = $this->request->data('page');
        $rows = $this->request->data('rows');
        $sort = 'Blog.' . $this->request->data('sidx');
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
        $query = $this->Blog->find();
        $query->hydrate(false);
        if (!empty($where)) {
            $query->where($where);
        }
        $nums = $query->count();
        $query->contain(['Blogcat', 'Admin']);
        if (!empty($sort) && !empty($order)) {
            $query->order([$sort => $order]);
        }

        $query->limit(intval($rows))
                ->page(intval($page));
        $res = $query->toArray();
        if (empty($res)) {
            $res = array();
        }
        if ($nums > 0) {
            $total_pages = ceil($nums / $rows);
        } else {
            $total_pages = 0;
        }
        $data = array('page' => $page, 'total' => $total_pages, 'records' => $nums, 'rows' => $res);
        $this->autoRender = false;
        $this->response->type('json');
        echo json_encode($data);
    }

    /**
     * export csv
     *
     * @return csv 
     */
    public function exportExcel() {
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
        $Table = $this->Blog;
        $column = ['category_id', 'admin_id', '标题', '引言', '封面', '内容', 'seo关键字', 'seo描述', '创建时间', '更新时间', '点击量'];
        $query = $Table->find();
        $query->hydrate(false);
        $query->select(['category_id', 'admin_id', 'title', 'guide', 'cover', 'content', 'keywords', 'description', 'ctime', 'updatetime', 'hits']);
        if (!empty($where)) {
            $query->where($where);
        }
        if (!empty($sort) && !empty($order)) {
            $query->order([$sort => $order]);
        }
        $res = $query->toArray();
        $this->autoRender = false;
        $filename = 'Blog_' . date('Y-m-d') . '.csv';
        \Wpadmin\Utils\Export::exportCsv($column, $res, $filename);
    }

}
