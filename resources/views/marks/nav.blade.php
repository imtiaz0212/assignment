<nav class="breadcrumb-two" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item heading">
            <a href="javascript:void(0);">Marks</a>
        </li>
        <li class="breadcrumb-item {{($submenu == 'marksCreate' ? 'active' : '')}}">
            <a href="{{route('admin.marks.create')}}">Add New</a>
        </li>
        <li class="breadcrumb-item {{($submenu == 'marksList' ? 'active' : '')}}">
            <a href="{{route('admin.marks')}}">View All</a>
        </li>
    </ol>
</nav>
