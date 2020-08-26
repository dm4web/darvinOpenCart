<?php
class ControllerCommonHome extends Controller
{
	public function index()
	{
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$products_darvin = $this->model_catalog_product->getNewForDarvin();

		$this->log->write($products_darvin);

		if ($products_darvin) {
			foreach ($products_darvin as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 200, 200);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 200, 200);
				}

				$data['products_darvin'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
		}

		$this->log->write($data['products_darvin']);

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
