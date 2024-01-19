<?php

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserGroupChanged extends Notification implements ShouldQueue
{
    use Queueable;

    private $user;
    private $oldGroupId;
    private $newGroupId;
    /**
     * Create a new notification instance.
     *
     * @param object $user 用户对象
     */
    public function __construct($user, $oldGroupId, $newGroupId)
    {
        $this->user = $user;
        $this->oldGroupId = $oldGroupId;
        $this->newGroupId = $newGroupId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * 获取用户组名称。
     *
     * @return string
     */
    private function getGroupName(): string
    {
        // 假设 Group 模型代表用户组，且有 name 字段
        $group = Group::find($this->user->group_id);

        return $group ? $group->name : '神秘组';
    }

    private function getGroupMessage($groupName): string
    {
        if ($this->newGroupId > $this->oldGroupId) {
            switch ($groupName) {
                case '入土':
                    return '您的账户因违反规则已被封禁';
                case '流放':
                    return '您的账户因长时间未登录已被冻结';
                case '坐寐':
                    return '由于长时间未登录，您的账户目前处于冻结状态';
                case '徭役':
                    return '由于分享率低于1.0，您已降级为徭役，请尽快提升您的分享率。';
                case '布衣':
                    return '您现在是KIMOJI的普通用户，请您熟悉我们的规则，祝您玩得开心';
                case '壮士':
                    return '恭喜您升级为壮士，感谢您的贡献！';
                case '力士':
                    return '恭喜您升级为力士，您的贡献对KIMOJI至关重要';
                case '剑客':
                    return '您已成为剑客，继续您的辉煌吧';
                case '大侠':
                    return '大侠，欢迎您！您的贡献是我们社区的骄傲';
                case '盟主':
                    return '您已被提升为盟主，感谢您的持续贡献和卓越领导';
                case '至尊':
                    return '恭喜您成为至尊，您的成就在社区中熠熠生辉。';
                case '剑圣':
                    return '恭喜您达到剑圣的崇高境界，无人能及！';
                case '耕夫':
                    return '感谢您的辛勤转种，您的等级已提升为耕夫，您的努力让社区资源更加丰富多彩，现在您享有邀请权，接下来请您维持每月最基本的转种量，努力保级';
                case '掌固':
                    return '感谢您的努力保种，您的等级已提升为掌固，您为KIMOJI的种子的健康与活力，这对我们至关重要，现在您享有邀请权，请您继续保持保种量';
                case '贵人':
                    return '您是KIMOJI的贵人，感谢您的赞助与贡献';
                case '颐养':
                    return '作为养老成员，您在功成身退后依然受到我们的尊敬与感激';
                case '统筹':
                    return '作为统筹，您是社区管理的核心，感谢您的智慧与决策';
                case '监护':
                    return '作为监护，您在维护秩序，保持KIMOJI健康方面发挥着重要作用';
                case '园丁':
                    return '作为园丁，您的努力让社区的内容始终保持高质量';
                case '守卫':
                    return '您作为守卫，确保了社区资源的规范性和准确性';
                default:
                    return '您的等级已更新';
            }
        } else {
            switch ($groupName) {
                case '入土':
                    return '您的账户因违反规则已被封禁';
                case '流放':
                    return '您的账户因长时间未登录已被冻结';
                case '坐寐':
                    return '由于长时间未登录，您的账户目前处于冻结状态';
                case '徭役':
                    return '由于分享率低于1.0，您已降级为徭役，请尽快提升您的分享率。';
                case '布衣':
                    return '您的官种保种量小于0.5TB，已降级至布衣，请您再接再厉';
                case '壮士':
                    return '您的官种保种量小于1.2TB，已降级至壮士，请您再接再厉';
                case '力士':
                    return '您的官种保种量小于2TB，已降级至力士，请您再接再厉';
                case '剑客':
                    return '您的官种保种量小于3TB，已降级至剑客，请您再接再厉';
                case '大侠':
                    return '您的官种保种量小于4.2TB，已降级至大侠，请您再接再厉';
                case '盟主':
                    return '您的官种保种量小于6TB，已降级至盟主，请您再接再厉';
                case '至尊':
                    return '您的官种保种量小于8TB，已降级至至尊，请您再接再厉';
                case '剑圣':
                    return '您的官种保种量小于10TB，已降级至剑圣，请您再接再厉';
                case '耕夫':
                    return '感谢您的辛勤转种，您的等级已提升为耕夫，您的努力让社区资源更加丰富多彩，现在您享有邀请权，接下来请您维持每月最基本的转种量，努力保级';
                case '掌固':
                    return '感谢您的努力保种，您的等级已提升为掌固，您为KIMOJI的种子的健康与活力，这对我们至关重要，现在您享有邀请权，请您继续保持保种量';
                case '贵人':
                    return '您是KIMOJI的贵人，感谢您的赞助与贡献';
                case '颐养':
                    return '作为养老成员，您在功成身退后依然受到我们的尊敬与感激';
                case '统筹':
                    return '作为统筹，您是社区管理的核心，感谢您的智慧与决策';
                case '监护':
                    return '作为监护，您在维护秩序，保持KIMOJI健康方面发挥着重要作用';
                case '园丁':
                    return '作为园丁，您的努力让社区的内容始终保持高质量';
                case '守卫':
                    return '您作为守卫，确保了社区资源的规范性和准确性';
                default:
                    return '您的等级已更新';
            }
        }
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $groupName = $this->getGroupName();
        $groupMessage = $this->getGroupMessage($groupName);

        return [
            'title' => '升级通知',
            'body'  => $groupMessage,
            'url'   => '/pages/8',
        ];
    }
}
