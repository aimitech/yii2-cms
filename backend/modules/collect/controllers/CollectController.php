<?php

namespace collect\controllers;

use backend\controllers\BaseController;
use common\models\CollectHtml;
use Yii;
use common\models\Collect;
use common\models\searchs\Collect as CollectSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class CollectController
 * @package collect\controllers
 */
class CollectController extends BaseController
{
    private $_cache = null;

    const CACHE_COLLECT_LIST = 'cache_collect_list'; //采集种子列表

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    //'start' => ['POST']
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->_cache = Yii::$app->cache;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * 列表
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CollectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 详情
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 添加
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new Collect();
        $model->encoding = 0;
        $model->status = 1;
        if(Yii::$app->request->isPost){
            if(!$model->load(Yii::$app->request->post())){
                exit('数据填充失败！');
            }
            $baseconfig = [
                'encoding' => $model->encoding,
                'is_head' => $model->is_head,
                'is_reverse' => $model->is_reverse,
                'is_ref' => $model->is_ref,
                'is_ref_url' => $model->is_ref_url,
                'is_thumb' => $model->is_thumb
            ];
            $rule_list = [];
            if($model->list_rules_url){$rule_list['url'] = [$model->list_rules_url,'href'];}
            if($model->list_rules_title){$rule_list['title'] = [$model->list_rules_title,'text'];}
            if($model->list_rules_thumb){$rule_list['thumb'] = [$model->list_rules_thumb,'src'];}
            //列表匹配规则
            $listconfig = [
                'list_url' => $model->list_url,
                'range' => $model->list_range,
                'rules' => $rule_list
            ];
            $rule_content = [];
            if($model->content_rules_title){$rule_content['title'] = [$model->content_rules_title,'text'];}
            if($model->content_rules_kw){$rule_content['keyword'] = [$model->content_rules_kw,'text'];}
            if($model->content_rules_desc){$rule_content['describtion'] = [$model->content_rules_desc,'text'];}
            if($model->content_rules_content){$rule_content['content'] = [$model->content_rules_content,'text',$model->content_rules_content_filter];}
            if($model->content_rules_author){$rule_content['author'] = [$model->content_rules_author,'text',$model->content_rules_author_filter];}
            if($model->content_rules_source){$rule_content['source'] = [$model->content_rules_source,'text',$model->content_rules_source_filter];}
            if($model->content_rules_click){$rule_content['click'] = [$model->content_rules_click,'text',$model->content_rules_click_filter];}
            if($model->content_rules_addtime){$rule_content['addtime'] = [$model->content_rules_addtime,'text',$model->content_rules_addtime_filter];}
            //内容匹配规则
            $arcconfig = [
                'range' => $model->content_range,
                'rules' => $rule_content
            ];
            $model->baseconfig = serialize($baseconfig);
            $model->listconfig = serialize($listconfig);
            $model->arcconfig = serialize($arcconfig);
            $model->create_addtime = GTIME;
            $model->create_user = Yii::$app->user->identity->username;
            if ($model->save(false)) {
                $res = json_decode(CollectHtml::initCollectList($model->id),true);
                if(!$res['status']){
                    exit($res['message']);
                }
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 更新
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost){
            if(!$model->load(Yii::$app->request->post())){
                exit('数据填充失败！');
            }
            $baseconfig = [
                'encoding' => $model->encoding,
                'is_head' => $model->is_head,
                'is_reverse' => $model->is_reverse,
                'is_ref' => $model->is_ref,
                'is_ref_url' => $model->is_ref_url,
                'is_thumb' => $model->is_thumb
            ];
            $rule_list = [];
            if($model->list_rules_url){$rule_list['url'] = [$model->list_rules_url,'href'];}
            if($model->list_rules_title){$rule_list['title'] = [$model->list_rules_title,'text'];}
            if($model->list_rules_thumb){$rule_list['thumb'] = [$model->list_rules_thumb,'src'];}
            //列表匹配规则
            $listconfig = [
                'list_url' => $model->list_url,
                'range' => $model->list_range,
                'rules' => $rule_list
            ];
            $rule_content = [];
            if($model->content_rules_title){$rule_content['title'] = [$model->content_rules_title,'text'];}
            if($model->content_rules_kw){$rule_content['kw'] = [$model->content_rules_kw,'text'];}
            if($model->content_rules_desc){$rule_content['desc'] = [$model->content_rules_desc,'text'];}
            if($model->content_rules_content){$rule_content['content'] = [$model->content_rules_content,'text',$model->content_rules_content_filter];}
            if($model->content_rules_author){$rule_content['author'] = [$model->content_rules_author,'text',$model->content_rules_author_filter];}
            if($model->content_rules_source){$rule_content['source'] = [$model->content_rules_source,'text',$model->content_rules_source_filter];}
            if($model->content_rules_click){$rule_content['click'] = [$model->content_rules_click,'text',$model->content_rules_click_filter];}
            if($model->content_rules_addtime){$rule_content['addtime'] = [$model->content_rules_addtime,'text',$model->content_rules_addtime_filter];}
            //内容匹配规则
            $arcconfig = [
                'range' => $model->content_range,
                'rules' => $rule_content
            ];
            $model->baseconfig = serialize($baseconfig);
            $model->listconfig = serialize($listconfig);
            $model->arcconfig = serialize($arcconfig);
            $model->update_user = Yii::$app->user->identity->username;
            if ($model->save(false)) {
                return $this->redirect(['index']);
            }
        }
        //渲染页面数据处理
        $listconfig = unserialize($model->listconfig);
        $list_rules = $listconfig['rules'];
        foreach ($list_rules as $key => $val){
            $listconfig[$key] = $val;
            if(isset($val[0])){
                $listconfig['list_rules_'.$key] = $val[0];
            }
        }
        $arcconfig = unserialize($model->arcconfig);
        $content_rules = $arcconfig['rules'];
        foreach ($content_rules as $key => $val){
            $arcconfig[$key] = $val;
            if(isset($val[0])){
                $arcconfig['content_rules_'.$key] = $val[0];
            }
            if(isset($val[2]) && in_array($key,['content_rules_content','content_rules_author','content_rules_source','content_rules_click','content_rules_addtime'])){
                $filter = 'content_rules_'.$key.'_filter';
                $arcconfig[$filter] = $val[2];
            }
        }
        unset($listconfig['rules'],$arcconfig['rules']);
        $baseconfig = unserialize($model->baseconfig);
        $attr = array_merge($baseconfig,$listconfig,$arcconfig);
        //单独赋值
        $model->list_range = $listconfig['range'];
        $model->content_range = $arcconfig['range'];
        $model->attributes = $attr;
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 删除
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * 测试采集
     * @param int $id
     * @return string
     */
    public function actionTest($id = 0){
        if(!$id){
            return ajaxReturnFailure('参数不能为空');
        }
        $query = new Collect();
        $conf = $query->getConf($id);
        $list = $query->getCollectionData($conf['list']['list_url'],$conf['list'],$conf['options']);
        if(!$list){
            return ajaxReturnFailure('暂无采集数据！');
        }
        $data = [];
        foreach ($list as $key => $val){
            $data[$key] = $query->getCollectionData($val['url'],$conf['content'],$conf['options']);
        }
        return $this->render('test',[
            'subject' => $conf['options']['subject'],
            'data' => $data
        ]);
    }

    /**
     * 采集内容
     * @param int $id
     * @return string
     */
    public function actionInitcollect($id){
        if(!$id){
            return ajaxReturnFailure('参数不能为空');
        }
        return $this->render('initcollect',[
            'id' => $id
        ]);
    }

    /**
     * 采集内容入库
     * @param $id
     * @return string
     */
    public function actionStart($id){
        set_time_limit(0);
        session_write_close(); //解决多Ajax请求造成session阻塞
        if(!$id){
            return ajaxReturnFailure('参数不能为空');
        }
        $list = CollectHtml::find()->select('id,c_id,title,url')->where(['c_id'=>$id,'is_down'=>0])->asArray()->all();
        if(!$list){
            return ajaxReturnFailure('暂无要采集的数据或已入库成功！');
        }
        $count = count($list);
        $succ_num = $err_num = 0;
        $query = new Collect();
        $conf = $query->getConf($id);
        foreach ($list as $key => $val){
            $data = $query->getCollectionData($val['url'],$conf['content'],$conf['options']);
            if($data){
                $res = CollectHtml::updateAll(['is_down'=>1,'content'=>serialize($data)],['id'=>$val['id'],'is_down'=>0]);
                ($res) ? $succ_num++ : $err_num++;
            }else{
                $err_num++;
            }
        }
        return ajaxReturnSuccess("共入库{$count}条数据，成功{$succ_num}条，失败{$err_num}条！");
    }

    /**
     * 获取采集列表（Ajax）
     * @param int $id
     * @return string
     */
    public function actionAjaxcollectlist($id = 0){
        if(!$id){
            return ajaxReturnFailure('参数不能为空');
        }
        $model = Collect::findOne($id);
        $data = CollectHtml::find()
            ->alias('ch')
            ->select('c.name,ch.*')
            ->leftJoin(Collect::tableName().' as c','c.id = ch.c_id')
            ->where(['c_id'=>$id])->asArray()->all();
        return json_encode([
            'code' => 0,
            'subject' => $model->name,
            'data' => $data
        ]);
    }

    /**
     * 删除采集列表（Ajax）
     * @param int $id
     * @param int $ids
     * @return string
     */
    public function actionAjaxcollectdel($id = 0,$ids = 0){
        if(!$ids){
            return ajaxReturnFailure('参数不能为空');
        }
        if(CollectHtml::deleteAll("id in({$ids})")){
            return ajaxReturnSuccess('删除成功');
        }
        return ajaxReturnFailure('删除失败');
    }

    /**
     * 刷新采集列表（Ajax）
     * @param int $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionAjaxcollectref($id = 0){
        if(!$id){
            return ajaxReturnFailure('参数不能为空');
        }
        $res = json_decode(CollectHtml::initCollectList($id),true);
        if($res['status']){
            return ajaxReturnSuccess('更新种子网址成功');
        }
        return ajaxReturnFailure($res['message']);
    }

    /**
     * 获取入库数据百分比
     * @param int $id
     * @return string
     */
    public function actionAjaxcollectstatus($id = 0){
        set_time_limit(0);
        session_write_close(); //解决多Ajax请求造成session阻塞
        if(!$id){
            return ajaxReturnFailure('参数不能为空');
        }
        $total = CollectHtml::find()->where(['c_id'=>$id])->count();
        $total_already = CollectHtml::find()->where(['c_id'=>$id,'is_down'=>1])->count();
        $persent = ($total_already / $total) * 100;
        return ajaxReturnSuccess('一切正常',ceil($persent));
    }

    /**
     * 模型
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Collect::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}