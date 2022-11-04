<nav class="breadcrumb-two" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item heading">
            <a href="javascript:void(0);">Exam Settings</a>
        </li>
        <li class="breadcrumb-item {{($submenu == 'examSettingCreate' ? 'active' : '')}}">
            <a href="{{route('admin.exam-setting.create')}}">Add New</a>
        </li>
        <li class="breadcrumb-item {{($submenu == 'examSettingList' ? 'active' : '')}}">
            <a href="{{route('admin.exam-setting')}}">View All</a>
        </li>
    </ol>
</nav>
