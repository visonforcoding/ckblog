<?php
namespace App\Controller;

use App\Controller\Admin\AppController;

/**
 * Blog Controller
 *
 * @property \App\Model\Table\BlogTable $Blog
 */
class BlogController extends AppController
{

/**
* Index method
*
* @return void
*/
public function index()
{
$this->set('blog', $this->Blog);
}

    /**
     * View method
     *
     * @param string|null $id Blog id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
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
    public function add()
    {
        $blog = $this->Blog->newEntity();
        if ($this->request->is('post')) {
            $blog = $this->Blog->patchEntity($blog, $this->request->data);
            if ($this->Blog->save($blog)) {
                 $this->Util->ajaxReturn(true,'添加成功');
            } else {
                 $errors = $blog->errors();
                 $this->Util->ajaxReturn(['status'=>false, 'msg'=>getMessage($errors),'errors'=>$errors]);
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
    public function edit($id = null)
    {
         $blog = $this->Blog->get($id,[
            'contain' => []
        ]);
        if ($this->request->is(['post','put'])) {
            $blog = $this->Blog->patchEntity($blog, $this->request->data);
            if ($this->Blog->save($blog)) {
                  $this->Util->ajaxReturn(true,'修改成功');
            } else {
                 $errors = $blog->errors();
               $this->Util->ajaxReturn(false,getMessage($errors));
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
    public function delete($id = null)
    {
        $this->request->allowMethod('post');
         $id = $this->request->data('id');
                if ($this->request->is('post')) {
                $blog = $this->Blog->get($id);
                 if ($this->Blog->delete($blog)) {
                     $this->Util->ajaxReturn(true,'删除成功');
                } else {
                    $errors = $blog->errors();
                    $this->Util->ajaxReturn(true,getMessage($errors));
                }
          }
    }
}
