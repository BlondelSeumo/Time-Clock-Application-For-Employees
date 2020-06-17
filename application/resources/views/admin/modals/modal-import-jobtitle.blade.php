<div class="ui modal tiny import">
    <div class="header">{{ __("Import Job Titles") }}</div>
    <div class="content">
    <form action="{{ url('import/fields/jobtitle') }}" class="ui form" method="post" accept-charset="utf-8"enctype="multipart/form-data" >
        @csrf
        <div class="field">
            <label>{{ __("Import CSV file") }}</label>
            <input class="ui file upload" value="" id="csvfile" name="csv" type="file" accept=".csv" onchange="validateFile()">
        </div>
    </div>
    <div class="actions">
        <button class="ui positive button approve" type="submit" name="submit"><i class="ui checkmark icon"></i> {{ __("Submit") }}</button>
        <button class="ui grey button cancel" type="button"><i class="ui times icon"></i> {{ __("Cancel") }}</button>
    </div>
    </form>  
</div>
