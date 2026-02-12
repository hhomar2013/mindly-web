<?php

namespace App\Livewire\Admins\CodeList;

use App\Helpers\QrGeneration;
use App\Helpers\switchActions;
use App\Models\code_list_body;
use App\Models\code_list_head;
use App\Models\Teacher;
use App\Models\teacher_wallet_transactions;
use App\Models\TeacherCourseOverview;
use App\Models\type_of_subscriptions;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CodeListIndex extends Component
{
    protected $listeners = ['deleteCodeList' => 'delete'];
    public $action = 'show';
    public $update = false;
    public $codeListId;
    public $codeList = [];
    public $codeListBody = [];
    public $teachers = [];
    public $selectedTeacher;
    public $selectedCourse;
    public $courses = [];
    public $resetFiales;
    public $typeOfSubscription;
    public $subscriptionId;
    public $subscriptionName;
    public $CreationCodeList = [];
    public $codeCount = 1;
    public $codePrice = 0;
    public $teacherInfo = [];
    public $totalPrice = 0;
    public $isBalanceEnough = false;
    public $showCodesList = [];
    public $selectedCodeLitsId;
    use switchActions, QrGeneration;




    public function showCodes($id)
    {
        $this->selectedCodeLitsId = $id;
        $this->action = 'show-codes';
        $this->showCodesList = code_list_body::query()->where('code_list_head_id', $id)->get();
        foreach ($this->showCodesList as $item) {
            $item->qr_code = $this->generateQrBase64($item->code);
        }
        // dd($this->showCodesList);
        $this->dispatch('message', message: __('Code list loaded successfully!'));
        // Optional: Log the first item's QR code for debugging

    }

    public function store()
    {
        $this->validate([
            'selectedTeacher' => 'required|numeric',
            'selectedCourse' => 'required|numeric',
            'CreationCodeList' => 'required|array',
        ]);

        $total_code_count = array_sum(array_column($this->CreationCodeList, 'code_count'));
        if ($this->isBalanceEnough) {
            $code_header = code_list_head::query()->create([
                'teacher_course_overviews_id' => $this->selectedCourse,
                'code_count' => $total_code_count,
                'created_by' => Auth::user()->id,
                'status' => 1
            ]);

            if ($code_header) {
                foreach ($this->CreationCodeList as $item) {
                    $code_count = $item['code_count'];
                    $code = code_list_body::generateNumericCode(14);
                    for ($i = 0; $i < $code_count; $i++) {
                        code_list_body::query()->create([
                            'code_list_head_id' => $code_header->id,
                            'type_of_subscription_id' => $item['subscriptionId'],
                            'code_price' => $item['code_price'],
                            'code' => $code,
                            'usage_count' => 1
                        ]);
                    }
                }
                $this->decreaseTeacherBalance($total_code_count);
            }

            $this->dispatch('message', message: __('Code list created successfully!'));
            $this->back();
        }
    }

    public function decreaseTeacherBalance($total_code_count)
    {
        $teacher = Teacher::with('wallet')->find($this->selectedTeacher);
        $teacher->wallet->balance -= $this->totalPrice;
        $teacher->wallet->save();

        $teacher_wallet_transaction = teacher_wallet_transactions::query()->create([
            'teacher_wallet_id' => $teacher->wallet->id,
            'amount' => $this->totalPrice,
            'type' => 'debit',
            'source' => 'إنشاء قائمة اكواد مكونه من ' . $total_code_count . ' كود',
            'balance_after' => $teacher->wallet->balance,
        ]);

        $this->dispatch('message', message: __('Teacher balance decreased successfully!'));
    }   

    public function calculateTotalPrice()
    {
        $this->totalPrice = 0;

        foreach ($this->CreationCodeList as $item) {
            $this->totalPrice += $item['code_count'] * $item['code_price'];
        }
    }

    public function checkTeacherBalance()
    {
        if (!$this->selectedTeacher) {
            return;
        }

        $teacher = Teacher::with('wallet')->find($this->selectedTeacher);

        if (!$teacher || !$teacher->wallet) {
            return;
        }

        if ($this->totalPrice > $teacher->wallet->balance) {
            $this->dispatch('error', message: __('Teacher balance is not enough!'));
            $this->isBalanceEnough = false;
            return false;
        }

        $this->isBalanceEnough = true;
        return true;
    }

    public function editCodeList($id)
    {
        $selected = $this->CreationCodeList[$id];
        $this->codeCount = $selected['code_count'];
        $this->subscriptionId = $selected['subscriptionId'];
        $this->codePrice = $selected['code_price'];
    } //editCodeList

    public function removeFromCodeList($index)
    {
        unset($this->CreationCodeList[$index]);
        $this->calculateTotalPrice();
    }   //removeFromCodeList

    public function addCodeToList()
    {
        $this->validate([
            'codeCount' => 'required|numeric|min:1',
            'subscriptionId' => 'required|numeric',
            'codePrice' => 'required|numeric|min:1',
        ]);

        $found = false;
        foreach ($this->CreationCodeList as $key => $item) {
            if ($item['subscriptionId'] == $this->subscriptionId) {
                $this->CreationCodeList[$key]['code_count'] += $this->codeCount;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $this->CreationCodeList[] = [
                'code_count' => $this->codeCount,
                'subscriptionId' => $this->subscriptionId,
                'subscriptionName' => $this->subscriptionName,
                'code_price' => $this->codePrice,
            ];
        }



        $this->calculateTotalPrice();

        $this->checkTeacherBalance();



        $this->reset(['codeCount', 'subscriptionId', 'subscriptionName', 'codePrice']);
    } //addCodeToList

    public function resetForm()
    {
        return  $this->resetFiales = ['totalPrice', 'selectedCourse', 'codeListBody', 'selectedTeacher', 'courses', 'codeListId', 'codeCount', 'subscriptionId', 'subscriptionName', 'CreationCodeList', 'codePrice'];
    }   //resetForm 

    public function updatedSubscriptionId($value)
    {
        $this->subscriptionName = $this->typeOfSubscription->where('id', $value)->first()->name;
    }   //updatedSubscriptionId     

    public function updatedSelectedTeacher()
    {
        $this->teacherInfo = Teacher::query()->with('wallet')->where('id', $this->selectedTeacher)->first();
        $this->courses = TeacherCourseOverview::query()->where('teacher_id', $this->selectedTeacher)->get();
    }   //updatedSelectedTeacher

    public function createCL()
    {
        $this->switchAction('create', false);
    }   //createCL

    public function editCL($id)
    {
        $this->switchAction('create', true, $this->resetForm());
        $this->codeListId = $id;
    }   //editCL

    public function back()
    {
        $this->mount();
        $this->switchAction('show', false, $this->resetForm());
    }   //back

    public function delete($id)
    {
        $codeList = code_list_head::query()->where('id', $id)->first();
        $codeList->delete();
        $this->mount();
        $this->dispatch('message', message: __('Code list deleted successfully!'));
    }   //delete

    public function mount()
    {
        $this->codeList = code_list_head::with('teacherCourseOverview.teacher')->get();
        $this->teachers = Teacher::query()->where('state', true)->get();
        $this->typeOfSubscription = type_of_subscriptions::query()->where('status', 1)->get();
    }   //mount

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admins.code-list.code-list-index', [
            'codeList' => $this->codeList,
            'typeOfSubscription' => $this->typeOfSubscription,
        ]);
    }   //render
}
