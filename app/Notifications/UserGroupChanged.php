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
                case 'Banned':
                    return 'Your account has been banned due to a violation of the rules.';
                case 'Pruned':
                    return 'Your account has been frozen due to prolonged inactivity.';
                case 'Disabled':
                    return 'Your account is currently disabled due to prolonged inactivity.';
                case 'Leech':
                    return 'Due to a share ratio below 1.0, you have been downgraded to Leech. Please improve your share ratio as soon as possible.';
                case 'User':
                    return 'You are now a regular user of KIMOJI. Please familiarize yourself with our rules, and have fun.';
                case 'PowerUser':
                    return 'Congratulations on being upgraded to PowerUser. Thank you for your contributions!';
                case 'SuperUser':
                    return 'Congratulations on being upgraded to SuperUser. Your contributions are crucial to KIMOJI.';
                case 'ExtremeUser':
                    return 'You have become an ExtremeUser. Continue your excellent work.';
                case 'InsaneUser':
                    return 'Welcome, InsaneUser! Your contributions are a pride of our community.';
                case 'Seeder':
                    return 'You have been promoted to Seeder. Thank you for your continuous contributions and outstanding leadership.';
                case 'Archivist':
                    return 'Congratulations on becoming an Archivist. Your achievements shine brightly in the community.';
                case 'Veteran':
                    return 'Congratulations on reaching the exalted status of a Veteran. Unparalleled!';
                case 'Internal':
                    return 'Thank you for your diligent reseeding. Your level has been upgraded to Internal. Your efforts make our community’s resources more diverse and colorful. You now have the right to invite. Please maintain the basic monthly reseeding volume and strive to keep your level.';
                case 'Keeper':
                    return 'Thank you for your efforts in seeding. Your level has been upgraded to Keeper. Your contributions to the health and vitality of KIMOJI’s seeds are crucial to us. You now have the right to invite. Please continue to maintain your seeding volume.';
                case 'VIP':
                    return 'You are a VIP of KIMOJI. Thank you for your sponsorship and contributions.';
                case 'Retiree':
                    return 'As a Retiree, you continue to be respected and appreciated after your successful completion and retirement.';
                case 'Administrator':
                    return 'As an Administrator, you are the core of community management. Thank you for your wisdom and decisions.';
                case 'Moderator':
                    return 'As a Moderator, you play a crucial role in maintaining order and keeping KIMOJI healthy.';
                case 'Uploader':
                    return 'As an Uploader, your efforts keep the content of our community high quality.';
                case 'Trustee':
                    return 'As a Trustee, you ensure the standardization and accuracy of community resources.';
                default:
                    return 'Your level has been updated.';
            }
        } else {
            switch ($groupName) {
                case 'Banned':
                    return 'Your account has been banned due to rule violations.';
                case 'Pruned':
                    return 'Your account has been frozen due to prolonged inactivity.';
                case 'Disabled':
                    return 'Your account is currently in a disabled state due to prolonged inactivity.';
                case 'Leech':
                    return 'Due to a share ratio below 1.0, you have been downgraded to Leech. Please improve your share ratio promptly.';
                case 'User':
                    return 'Your official seeding volume is less than 0.5TB, and you have been downgraded to User. Please strive for further progress.';
                case 'PowerUser':
                    return 'Your official seeding volume is less than 1.2TB, and you have been downgraded to PowerUser. Please strive for further progress.';
                case 'SuperUser':
                    return 'Your official seeding volume is less than 2TB, and you have been downgraded to SuperUser. Please strive for further progress.';
                case 'ExtremeUser':
                    return 'Your official seeding volume is less than 3TB, and you have been downgraded to ExtremeUser. Please strive for further progress.';
                case 'InsaneUser':
                    return 'Your official seeding volume is less than 4.2TB, and you have been downgraded to InsaneUser. Please strive for further progress.';
                case 'Seeder':
                    return 'Your official seeding volume is less than 6TB, and you have been downgraded to Seeder. Please strive for further progress.';
                case 'Archivist':
                    return 'Your official seeding volume is less than 8TB, and you have been downgraded to Archivist. Please strive for further progress.';
                case 'Veteran':
                    return 'Your official seeding volume is less than 10TB, and you have been downgraded to Veteran. Please strive for further progress.';
                case 'Internal':
                    return 'Thank you for your diligent reseeding. Your rank has been upgraded to Internal. Your efforts have enriched our community’s resources significantly. You now have invitation rights. Please maintain the basic monthly reseeding volume and strive to keep your rank.';
                case 'Keeper':
                    return 'Thank you for your efforts in seeding. Your rank has been upgraded to Keeper. Your contributions to the health and vitality of KIMOJI’s seeds are crucial to us. You now have invitation rights. Please continue to maintain your seeding volume.';
                case 'VIP':
                    return 'You are a VIP of KIMOJI, and we thank you for your sponsorship and contributions.';
                case 'Retiree':
                    return 'As a Retiree, you continue to receive our respect and gratitude after retiring from active contributions.';
                case 'Administrator':
                    return 'As an Administrator, you are at the core of our community management. Thank you for your wisdom and decision-making.';
                case 'Moderator':
                    return 'As a Moderator, you play a crucial role in maintaining order and keeping KIMOJI healthy.';
                case 'Uploader':
                    return 'As an Uploader, your efforts ensure our community’s content remains high quality.';
                case 'Trustee':
                    return 'As a Trustee, you ensure the standardization and accuracy of our community resources.';
                default:
                    return 'Your rank has been updated.';
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
            'title' => 'Rank Change Notification',
            'body'  => $groupMessage,
            'url'   => '/wikis/1',
        ];
    }
}
