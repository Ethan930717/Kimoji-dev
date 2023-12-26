<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * 显示任务列表。
     */
    public function index()
    {
        $tasks = Task::all(); // 获取所有任务

        return view('tasks.index', ['tasks' => $tasks]);
    }

    /**
     * 显示创建任务的表单。
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * 存储新任务。
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'deadline'    => 'required|integer|min:1', // 期限（天）
            'reward'      => 'required|numeric|min:100' // 赏金
        ]);

        $task = new Task();
        $task->fill($validatedData);
        $task->save();

        return redirect()->route('tasks.index')->with('success', '任务创建成功！');
    }

    /**
     * 显示任务详情。
     */
    public function show(Task $task)
    {
        return view('tasks.show', ['task' => $task]);
    }

    /**
     * 显示编辑任务的表单。
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', ['task' => $task]);
    }

    /**
     * 更新任务。
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'deadline'    => 'required|integer|min:1', // 期限（天）
            'reward'      => 'required|numeric|min:100' // 赏金
        ]);

        $task->fill($validatedData);
        $task->save();

        return redirect()->route('tasks.show', $task)->with('success', '任务更新成功！');
    }

    /**
     * 删除任务。
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', '任务删除成功！');
    }
}
