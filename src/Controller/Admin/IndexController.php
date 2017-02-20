<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Index Controller
 *
 * @property \App\Model\Table\IndexTable $Index
 */
class IndexController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        

        $this->set(compact('index'));
        $this->set('_serialize', ['index']);
    }

    /**
     * View method
     *
     * @param string|null $id Index id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $index = $this->Index->get($id, [
            'contain' => []
        ]);

        $this->set('index', $index);
        $this->set('_serialize', ['index']);
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
