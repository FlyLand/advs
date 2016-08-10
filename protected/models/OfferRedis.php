<?php

/**
 * This is the model class for table "joy_offers".
 *
 * The followings are the available columns in table 'joy_offers':
 * @property integer $id
 * @property integer $advertiser_id
 * @property string $name
 * @property string $description
 * @property string $preview_url
 * @property string $offer_url
 * @property string $protocol
 * @property string $expiration_date
 * @property integer $offer_category
 * @property integer $ref_id
 * @property string $currency
 * @property string $revenue_type
 * @property double $revenue
 * @property string $payout_type
 * @property double $payout
 * @property string $thumbnail
 * @property integer $caps
 * @property integer $is_private
 * @property integer $require_approval
 * @property integer $require_terms_and_conditions
 * @property integer $is_seo_friendly_301
 * @property integer $email_instructions
 * @property integer $show_mail_list
 * @property string $redirect_offer_id
 * @property integer $session_hours
 * @property integer $tracking
 * @property integer $session_impression_hours
 * @property integer $enable_offer_whitelist
 * @property integer $status
 * @property string $note
 * @property string $campaign_id
 * @property string $createtime
 * @property string $joy_createtime
 * @property string $updatetime
 * @property string $geo_targeting
 * @property string $type
 * @property string $platform
 * @property string $traffic
 * @property string $recommend
 * @property string $min_android_version
 * @property string $max_android_version
 * @property string $create_self
 */
class OfferRedis extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return joy_offers the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $handle = new OfferHandle();
        $handle->unsetRedis();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'joy_offers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('revenue, payout', 'required'),
            array('advertiser_id, offer_category, ref_id, caps, is_private, require_approval, require_terms_and_conditions, is_seo_friendly_301, email_instructions, show_mail_list, session_hours, session_impression_hours, enable_offer_whitelist, status', 'numerical', 'integerOnly' => true),
            array('revenue, payout', 'numerical'),
            array('name, redirect_offer_id', 'length', 'max' => 255),
            array('preview_url, offer_url', 'length', 'max' => 800),
            array('protocol', 'length', 'max' => 50),
            array('currency, revenue_type, payout_type', 'length', 'max' => 20),
            array('description, expiration_date, note, createtime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, advertiser_id, name, description, preview_url, offer_url, protocol, expiration_date, offer_category, ref_id, currency, revenue_type, revenue, payout_type, payout, caps, is_private, require_approval, require_terms_and_conditions, is_seo_friendly_301, email_instructions, show_mail_list, redirect_offer_id, session_hours, session_impression_hours, enable_offer_whitelist, status, note, createtime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'adv' => array(self::BELONGS_TO, 'JoySystemUser', '', 'on' => 'adv.id=t.advertiser_id'),
            'caps' => array(self::BELONGS_TO, 'JoyOffersCaps', '', 'on' => 'caps.offer_id=t.id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'advertiser_id' => 'Advertiser',
            'name' => 'Name',
            'description' => 'Description',
            'preview_url' => 'Preview Url',
            'offer_url' => 'Offer Url',
            'protocol' => 'Protocol',
            'expiration_date' => 'Expiration Date',
            'offer_category' => 'Offer Category',
            'ref_id' => 'Ref',
            'currency' => 'Currency',
            'revenue_type' => 'Revenue Type',
            'revenue' => 'Revenue',
            'payout_type' => 'Payout Type',
            'payout' => 'Payout',
            'thumbnail' => 'Thumbnail',
            'caps' => 'Caps',
            'is_private' => 'Is Private',
            'require_approval' => 'Require Approval',
            'require_terms_and_conditions' => 'Require Terms And Conditions',
            'is_seo_friendly_301' => 'Is Seo Friendly 301',
            'email_instructions' => 'Email Instructions',
            'show_mail_list' => 'Show Mail List',
            'redirect_offer_id' => 'Redirect Offer',
            'session_hours' => 'Session Hours',
            'session_impression_hours' => 'Session Impression Hours',
            'enable_offer_whitelist' => 'Enable Offer Whitelist',
            'status' => 'Status',
            'note' => 'Note',
            'tracking' => 'Tracking',
            'campaign_id' => 'Campaign Id',
            'createtime' => 'Createtime',
            'joy_createtime' => 'Joy_Createtime',
            'updatetime' => 'Updatetime',
            'geo_targeting' => 'Geo Targeting',
            'type' => 'Type',
            'platform' => 'Platform',
            'traffic' => 'Traffic',
            'max_android_version' => 'Max Android Version',
            'min_android_version' => 'Min Android Version',
            'create_self' => 'create_self',
            'recommend' => 'Recommend'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('advertiser_id', $this->advertiser_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('preview_url', $this->preview_url, true);
        $criteria->compare('offer_url', $this->offer_url, true);
        $criteria->compare('protocol', $this->protocol, true);
        $criteria->compare('expiration_date', $this->expiration_date, true);
        $criteria->compare('offer_category', $this->offer_category);
        $criteria->compare('ref_id', $this->ref_id);
        $criteria->compare('currency', $this->currency, true);
        $criteria->compare('revenue_type', $this->revenue_type, true);
        $criteria->compare('revenue', $this->revenue);
        $criteria->compare('payout_type', $this->payout_type, true);
        $criteria->compare('payout', $this->payout);
        $criteria->compare('caps', $this->caps);
        $criteria->compare('is_private', $this->is_private);
        $criteria->compare('require_approval', $this->require_approval);
        $criteria->compare('require_terms_and_conditions', $this->require_terms_and_conditions);
        $criteria->compare('is_seo_friendly_301', $this->is_seo_friendly_301);
        $criteria->compare('email_instructions', $this->email_instructions);
        $criteria->compare('show_mail_list', $this->show_mail_list);
        $criteria->compare('redirect_offer_id', $this->redirect_offer_id, true);
        $criteria->compare('session_hours', $this->session_hours);
        $criteria->compare('session_impression_hours', $this->session_impression_hours);
        $criteria->compare('enable_offer_whitelist', $this->enable_offer_whitelist);
        $criteria->compare('status', $this->status);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('tracking', $this->tracking, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('joy_createtime', $this->joy_createtime, true);
        $criteria->compare('updatetime', $this->updatetime, true);
        $criteria->compare('thumbnail', $this->thumbnail, true);
        $criteria->compare('campaign_id', $this->campaign_id, true);
        $criteria->compare('geo_targeting', $this->geo_targeting, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('traffic', $this->traffic, true);
        $criteria->compare('platform', $this->platform, true);
        $criteria->compare('max_android_version', $this->max_android_version, true);
        $criteria->compare('min_android_version', $this->min_android_version, true);
        $criteria->compare('create_self', $this->create_self, true);
        $criteria->compare('recommend', $this->recommend, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function setOfferPending($advid = null)
    {
        if (!empty($advid)) {
            $result = joy_offers::model()->updateAll(array('status' => 0), "advertiser_id = $advid and create_self=0");
            if (empty($result)) {
                $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advid));
                $offer->status = 1;
                $offer->update();
            }
        }
    }

    public static function getOfferFlied($fields = '*', $condition = '', $order_by = '', $limit = null, $query_row)
    {
        $db = Yii::app()->db;
        $select_sql = "select $fields from joy_offers where 1 =1 $condition $order_by";
        if (!empty($limit)) {
            $select_sql .= " limit $limit";
        }
        $command = $db->createCommand($select_sql);
        if ($query_row)
            $result = $command->queryRow();
        else
            $result = $command->queryAll();
        return $result;
    }

    public function getMonitorName()
    {
        return array($this->tableName(), JoyJump::model()->tableName());
    }

    public static function changeMonitor($table_name, $action, $params, $condition = array())
    {
        $model = '';
        $result = false;
        if ($table_name == 'joy_offers') {
            switch ($action) {
                case 'add' :
                    $model = new joy_offers();
                    foreach ($params as $item => $key) {
                        $model->$item = $key;
                    }
                    break;
                case 'update' :
                    $model = joy_offers::model()->findAllByAttributes($condition);
                    foreach ($params as $item => $key) {
                        $model->$item = $key;
                    }
                    break;
            }
        } elseif ($table_name == 'joy_jump') {
            switch ($action) {
                case 'add' :
                    $model = new JoyJump();
                    foreach ($params as $item => $key) {
                        $model->$item = $key;
                    }
                    break;
                case 'update':
                    $model = JoyJump::model()->findAllByAttributes($condition);
                    foreach ($params as $item => $key) {
                        $model->$item = $key;
                    }
                    break;
            }
        }
        if (!empty($model) && $model->save()) {
            $result = true;
        }
        return $result;
    }
}