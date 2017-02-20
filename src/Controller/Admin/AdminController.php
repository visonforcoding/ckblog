<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Admin Controller
 *
 * @property \Admin\Model\Table\AdminTable $Admin
 */
class AdminController extends AppController {

    /**
     * Index method
     *
     * @return void
     */
    public function index() {
//        var_dump($this->Admin->find()->hydrate(false)->contain('g')->first());
        $this->set('admin', $this->Admin);
    }

    /**
     * View method
     *
     * @param string|null $id Admin id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null) {
        $admin = $this->Admin->get($id, [
            'contain' => []
        ]);
        $this->set('admin', $admin);
        $this->set('_serialize', ['admin']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $admin = $this->Admin->newEntity();
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $this->response->type('json');
            $admin = $this->Admin->patchEntity($admin, $this->request->data);
            $admin->password = '123456';
            $admin->enabled = 1;
            if ($this->Admin->save($admin)) {
                echo json_encode(array('status' => true, 'msg' => '添加成功'));
            } else {
                $errors = $admin->errors();
                echo json_encode(array('status' => false, 'msg' => getMessage($errors)));
            }
            return;
        }
        $g = $this->Admin->g->find('list', ['limit' => 200]);
        $this->set(compact('admin', 'g'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $admin = $this->Admin->get($id, [
            'contain' => ['g.menu']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $admin = $this->Admin->patchEntity($admin, $this->request->data);
            if ($this->Group->save($admin)) {
                echo json_encode(array('status' => true, 'msg' => '修改成功'));
            } else {
                $errors = $admin->errors();
                echo json_encode(array('status' => false, 'msg' => getMessage($errors)));
            }
            return;
        }
        $g = $this->Admin->g->find('list', ['limit' => 200]);
        $this->set(compact('admin', 'g'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Admin id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod('post');
        $id = $this->request->data('id');
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $this->response->type('json');
            $admin = $this->Admin->get($id);
            if ($this->Admin->delete($admin)) {
                echo json_encode(array('status' => true, 'msg' => '删除成功'));
            } else {
                $errors = $admin->errors();
                echo json_encode(array('status' => false, 'msg' => getMessage($errors)));
            }
        }
        return;
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
        $query = $this->Admin->find();
        $query->hydrate(false);
        if (!empty($where)) {
            $query->where($where);
        }
        $nums = $query->count();
        $query->contain(['g']);
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

    public function login() {
        $this->viewBuilder()->autoLayout(false);
        if ($this->request->is('post')) {
            $username = $this->request->data('username');
            $password = $this->request->data('password');
            $query_admin = $this->Admin->find();
//            $query_admin->contain(['g.menu']);
            $admin = $query_admin->where("enabled = 1 and username = '$username'")->first();
            if (!$admin) {
                return $this->Flash->error('该用户不存在！');
            }
            $ck = (new \Cake\Auth\DefaultPasswordHasher)->check($password, $admin->password);
            if (!(new \Cake\Auth\DefaultPasswordHasher)->check($password, $admin->password)) {
                return $this->Flash->error('密码不正确！');
            }
            //登录成功
            unset($admin->password);
            $this->request->session()->write('User.admin', $admin);  //记录session
            $admin->last_login = date('Y-m-d H:i:s');
            $admin->login_ip = $this->request->clientIp();
            $this->Admin->save($admin);
//            $this->Util->actionLog('登录系统', $admin->username);
            return $this->redirect('/admin/index/index');
        }
    }

    /**
     * 
     * @return type
     */
    public function logout() {
        $this->viewBuilder()->autoLayout(false);
//        $this->request->session()->delete('User.admin');
        $this->request->session()->destroy();
        $this->Flash->success('您已退出登录！');
        return $this->redirect(['controller' => 'admin', 'action' => 'login']);
    }

}
