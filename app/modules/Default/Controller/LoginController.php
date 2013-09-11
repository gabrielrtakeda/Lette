<?php
class Default_LoginController extends Controller
{
		public function loginAction()
		{
				$json = array();
				$post		= $this->request->post;
				
				$model		= $this->getModel('default_login');
				$user			= $model->userLogin($post['login_uid'], $post['login_pwd']);
				$userInf	= $user->FETCH_OBJECT;
				
				if (($user->NUM_ROW != 0) && (!$userInf[0]->WebBloq)) {
						$login	= array(
								'cod'			=> $user->FETCH_OBJECT[0]->Codigo,
								'apelido' => $user->FETCH_OBJECT[0]->Apelido,
								'senha'		=> $user->FETCH_OBJECT[0]->WebSenha,
								'bloq'		=> $user->FETCH_OBJECT[0]->WebBloq,
						);
						$this->session->set('login', $login);
						
						$firma				= $model->getDefaultFirma();
						$firmaDefault	= array(
								'id'				=> $firma->IDFirma,
								'cnpj'			=> $firma->CNPJ,
								'fantasia'	=> $firma->Fantasia,
								'razao'			=> $firma->Razao,
						);
						$this->session->set('firma', $firmaDefault);
						if ($this->session->has('login')) $json['success'] = true;
				}
				echo json_encode($json);
		}
		
		public function logoutAction() {
				$this->session->clear();
				$this->url->redirect('');
		}
		
		public function hasPermissionAction()
		{
				$session	= $this->session->get('login');
				$model		= $this->getModel('default_login');
				$compare	= $model->compare(array('Apelido' => $session['apelido'], 'WebSenha' => $session['senha'], 'WebBloq' => $session['bloq']));
				
				echo ($compare ? 'true' : 'false');
		}
}