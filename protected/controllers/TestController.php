<?php
Class TestController extends Controller{
    public function actionCache(){
        $db = Yii::app()->db;
        $sql = "select u.company as aff_name,t.count_date as mon ,t.affid,t.amount,t.id,t.fee,m.company as am_name from joy_invoice t LEFT JOIN
joy_system_user u on u.id=t.affid LEFT JOIN
joy_system_user m on t.am_id=m.id
ORDER BY aff_name";
        $command = $db->createCommand($sql);
        $result = $command->queryAll();
        $head_arr = array('Payee',	'Month',	'ID',	'Amount Payable','Total','Balance','AM');
        /*$head = $result[0];
        $head_arr = array();
        while ($head_name = key($head)) {
            array_push($head_arr,$head_name);
            next($head);
        }*/
        Tools::download_excel($head_arr,$result);
    }

    public function actionOfferHandle(){
        $filter = new JumpFilter('CountryHandle');
        var_dump($filter->setMembers());
    }

    public function actionSetAllRedis(){
        $apiHandle = new ApiFilter();
        var_dump($apiHandle->setAllFilter());
    }

    public function actionTestPay(){
//        JoyCountPay::countPay($date);
    }

    public function actionTestConnectType(){
        $handle = new ConnectionHandle();
        $handle->getFilterRelation();
    }

    public function actionTestCountryFilter(){
        $result = array();
        $parent_class = new ReflectionClass('CountryHandle');
        $parent_instance = $parent_class->newInstanceArgs();
        $parent_id_arr = $parent_instance->getFilterRelation();

        $filter = new JumpFilter('CountryHandle');
        if(!empty($parent_id_arr)){
            $score = 0;
            foreach($parent_id_arr as $parent_id=>$sub){
                foreach($sub as $subid){
                        array_push($result,$filter->setFilterList($parent_id,$subid,$score));
                    }
                    $score++;
                }
            }

//        $filter = new CountryHandle();
//        var_dump($filter->getFilterRelation());
    }
    public function actionTestConfig(){
        $config = new JumpFilter('JumpHandle');
        $config_params = $config->getConfigParams('264','us');
        if(isset($config_params['optimal_offer'])){
            $offerid = $config_params['optimal_offer'];
        }
        if(isset($config_params['optimal_connect'])){
            $connect = $config_params['optimal_connect'];
        }
    }

    public function actionTestSetToMysql(){
        $handle = new SqlHandle();
        $handle->setToMysql();
    }

    public function actionTransaction()
    {
        $result = JoySystemUser::model()->findAllByAttributes(array('groupid' => AFF_GROUP_ID));
        $name = 'name_1';
        foreach ($result as $item) {
            $item_new = new JoySystemUser();
            $site_id = JoySystemUser::getSystemId();
            $item_new->id = $site_id;
            $item_new->email = $item['email'];
            $item_new->password = $item['password'];
            $item_new->groupid = SITE_GROUP_ID;
            $item_new->first_name = $item['first_name'];
            $item_new->last_name = $item['last_name'];
            $item_new->title = $item['title'];
            $item_new->cutcount = $item['cutcount'];
            $item_new->company = $item['company'];
            $item_new->address = $item['address'];
            $item_new->address2 = $item['address2'];
            $item_new->city = $item['city'];
            $item_new->region = $item['region'];
            $item_new->country = $item['country'];
            $item_new->zipcode = $item['zipcode'];
            $item_new->phone = $item['phone'];
            $item_new->postback = $item['postback'];
            $item_new->manager_userid = $item['manager_userid'];
            $item_new->status = $item['status'];
            $item_new->logincount = $item['logincount'];
            $item_new->lastmodify = $item['lastmodify'];
            $item_new->lastlogin = $item['lastlogin'];
            $item_new->loginip = $item['loginip'];
            $item_new->createtime = $item['createtime'];
            $item_new->verify = $item['verify'];

            $aff_id = $item->id;
            $email = $item['email'] . $name;
            $item->email = $email;
            $item->save();
            if ($item_new->save()) {
                JoySystemUser::incSystemId($site_id);
                $site = new JoySites();
                $site->site_id = $site_id;
                $site->affids = $aff_id;
                $site->save();
            }
        }
    }
}