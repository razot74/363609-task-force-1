<?php

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in_work';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'cancel';
    const ACTION_RESPOND = 'respond';
    const ACTION_DONE = 'done';
    const ACTION_REFUSE = 'refuse';

    const STATUSES_LIST = [
          self::STATUS_NEW => 'Новое',
          self::STATUS_CANCELED => 'Отменено',
          self::STATUS_IN_WORK => 'В работе',
          self::STATUS_COMPLETED => 'Выполнено',
          self::STATUS_FAILED => 'Провалено',
    ];

    const ACTIONS_LIST = [
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_DONE => 'Выполнено',
        self::ACTION_REFUSE => 'Отказаться',
    ];

    const AVAILABLE_ACTIONS_MAP = [
        self::STATUS_NEW => [
            self::ACTION_CANCEL,
            self::ACTION_RESPOND
        ],
        self::STATUS_IN_WORK => [
            self::ACTION_DONE,
            self::ACTION_REFUSE
        ],
    ];

    private $executorId;
    private $customerId;
    private $currentStatus;

    public function __construct($executor, $customer)
    {
        $this->executorId = $executor;
        $this->customerId = $customer;
        //тут устанавливаем статус из бд, пока пусть будет новый
        $this->currentStatus = self::STATUS_NEW;
    }

    public function getAvailableActions()
    {
        return self::AVAILABLE_ACTIONS_MAP[$this->currentStatus] ?? null;
    }

    public function getNextStatus($action)
    {
        switch (true) {

            case $action === self::ACTION_CANCEL && $this->currentStatus === self::STATUS_NEW:

                return self::STATUS_CANCELED;
                break;

            case self::ACTION_RESPOND:

                if($this->currentStatus == self::STATUS_NEW) {
                    $nextStatus = self::STATUS_IN_WORK;
                }
                break;

            case $action === self::ACTION_DONE && $this->currentStatus == self::STATUS_IN_WORK:

                return self::STATUS_COMPLETED;
                break;

            case $action === self::ACTION_REFUSE && $this->currentStatus == self::STATUS_IN_WORK:

                return self::STATUS_FAILED;
                break;

            default:
                return null;

        }
    }
}
