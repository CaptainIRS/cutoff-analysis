@extends('layouts.app')

@push('title', 'Using the JoSAA Analysis tool | News &amp; Updates')

@section('meta')
    <link rel="amphtml" href="{{ route('news.amp.using-the-josaa-analysis-tool') }}">
    <meta name="description"
        content="How to use JoSAA Analysis - a web application that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, iits, nits, iiits">

    <meta property="og:title" content="Using the JoSAA Analysis tool - JoSAA Analysis">
    <meta property="og:description"
        content="How to use JoSAA Analysis - a web application that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Using the JoSAA Analysis tool - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('home') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="How to use JoSAA Analysis - a web application that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <article class="container">
        <h1 class="text-3xl font-bold m-4">Using the JoSAA Analysis tool</h1>
        <h5 class="text-sm m-4">Published on 13th September 2022</h5>

        <source srcset="{{ asset('images/dark/josaa-analysis-institute-trends.png') }}"
            media="(prefers-color-scheme: dark)">
        <img class="lg:max-w-3xl m-auto" src="{{ asset('images/light/josaa-analysis-institute-trends.png') }}"
            alt="Screenshot of the institute trends feature of the JoSAA Analysis tool">

        <p class="text-md m-4">
            The JoSAA counselling process is a very important part of the engineering admission process. It is the time when
            students decide which college they want to go to and which branch they want to study. The process for 2022 has
            already started and will continue till the 21st of October as per the <a
                class="text-indigo-600 hover:text-indigo-900" href="https://josaa.nic.in/schedule/" target="_blank">official
                schedule</a>.
        </p>

        <p class="text-md m-4">
            During the counselling process, students have to select and arrange their choices for the colleges and branches
            they want to go to. This requires a lot of research and analysis of the previous year cut-offs, so that they
            can make an informed decision. The official data provided by JoSAA is not very easy to understand and requires
            a lot of time to analyse as it is not presented in a very user-friendly manner. This is where the JoSAA Analysis
            tool comes in.
        </p>

        <p class="text-md m-4">
            The JoSAA Analysis tool is a web application that helps you decide your choices for JoSAA counselling based on
            10 years of cut-off data. It is a very simple and easy to use tool, which can be used by anyone. It is
            completely free and open source, and you can find the source code on <a
                href="https://github.com/CaptainIRS/josaa-analysis" target="_blank">GitHub</a>.
        </p>

        <p class="text-md m-4">
            The tool offers ways to filter and visualise the past cut-off data, using the parameters provided by the user
            like branch preference, institute preference, institute type, quota, seat type, gender, etc. The data is
            obtained from the official JoSAA website, and will be updated every year. Data is available for 2012, 2013,
            2014, 2015, 2016, 2017, 2018, 2019, 2020 and 2021, and includes the opening and closing ranks for all the rounds
            of counselling. This helps users to get a better idea of the trends in the cut-offs over the years, and make a
            more informed decision about their choices.
        </p>

        <p class="text-md m-4">
            Get started by visiting the <a class="text-indigo-600 hover:text-indigo-900" href="{{ route('home') }}"
                target="_blank">JoSAA Analysis tool</a>.
        </p>


        <p class="text-md m-4">
            Here are the features of the JoSAA Analysis tool and how to use them:
        </p>

        <h3 class="text-xl font-bold m-4">View Institute-wise Cut-offs</h3>
        <source srcset="{{ asset('images/dark/josaa-analysis-filter-by-institute.png') }}"
            media="(prefers-color-scheme: dark)">
        <img class="lg:max-w-3xl m-auto" src="{{ asset('images/light/josaa-analysis-filter-by-institute.png') }}"
            alt="Screenshot of the filter by institute feature of the JoSAA Analysis tool">
        <p class="text-md m-4">
            This feature allows you to filter the past cut-offs by institute. Select one or many institutes using the
            dropdown menu and then further narrow down the data using the program data. Users can also specify the minimum
            and maximum cut-off rank to be displayed so that they can get an idea of the programs in the selected institutes
            that might be suitable for their rank range.
        </p>
        <p class="text-md m-4">
            The data is displayed in a table format, with the opening and closing ranks for each round of counselling. The
            table can be sorted by any column by clicking on the column header.
        </p>
        <p class="text-md m-4">
            Try it out now by visiting the <a class="text-indigo-600 hover:text-indigo-900"
                href="{{ route('search-by-institute') }}" target="_blank">link</a>.
        </p>

        <h3 class="text-xl font-bold m-4">Filter by Program</h3>
        <source srcset="{{ asset('images/dark/josaa-analysis-filter-by-program.png') }}"
            media="(prefers-color-scheme: dark)">
        <img class="lg:max-w-3xl m-auto" src="{{ asset('images/light/josaa-analysis-filter-by-program.png') }}"
            alt="Screenshot of the filter by program feature of the JoSAA Analysis tool">
        <p class="text-md m-4">
            This feature allows you to filter the past cut-offs by branch. Select one or many branches using the dropdown
            menu and then further narrow down the data using the institute data. The branches available in the dropdown are
            fields of engineering like Computer Science and IT, Civil, etc. and aggregates like Circuital and Non-Circuital.
            Users can also specify the minimum and maximum cut-off rank to be displayed so that they can get an idea of the
            institutes offering the selected branches that might be suitable for their rank range.
        </p>
        <p class="text-md m-4">
            The data is displayed in a table format, with the opening and closing ranks for each round of counselling. The
            table can be sorted by any column by clicking on the column header.
        </p>
        <p class="text-md m-4">
            Try it out now by visiting the <a class="text-indigo-600 hover:text-indigo-900"
                href="{{ route('search-by-branch') }}" target="_blank">link</a>.
        </p>

        <h3 class="text-xl font-bold m-4">Analyse Branch-wise Cut-off Trends</h3>
        <source srcset="{{ asset('images/dark/josaa-analysis-branch-trends.png') }}" media="(prefers-color-scheme: dark)">
        <img class="lg:max-w-3xl m-auto" src="{{ asset('images/light/josaa-analysis-branch-trends.png') }}"
            alt="Screenshot of the branch trends feature of the JoSAA Analysis tool">
        <p class="text-md m-4">
            This feature allows you to visualise the trends of cut-offs of various institutes offering courses in a
            particular branch of engineering over the years. This gives an idea about the popularity and perception of
            various institutes offering courses in a branch of engineering among engineering aspirants.
        </p>
        <p class="text-md m-4">
            The data is displayed in a line graph format, with the closing ranks for each round of counselling. The legend
            at the top of the graph can be used to toggle the visibility of the data for a particular institute. The graph
            can be zoomed in and out using the mouse wheel or by pinching on a touch screen.
            The graph can also be panned by clicking and dragging on the graph. The data points can be hovered over or
            tapped to see the exact values.
        </p>
        <p class="text-md m-4">
            Try it out now by visiting the <a class="text-indigo-600 hover:text-indigo-900"
                href="{{ route('branch-trends') }}" target="_blank">link</a>.
        </p>

        <h3 class="text-xl font-bold m-4">Analyse Institute-wise Cut-off Trends</h3>
        <source srcset="{{ asset('images/dark/josaa-analysis-institute-trends.png') }}"
            media="(prefers-color-scheme: dark)">
        <img class="lg:max-w-3xl m-auto" src="{{ asset('images/light/josaa-analysis-institute-trends.png') }}"
            alt="Screenshot of the institute trends feature of the JoSAA Analysis tool">
        <p class="text-md m-4">
            This feature allows you to visualise the trends of cut-offs of various branches offered by an institute over the
            years. This gives an idea about the popularity and perception of various branches offered by an institute among
            engineering aspirants.
        </p>
        <p class="text-md m-4">
            The data is displayed in a line graph format, with the closing ranks for each round of counselling. The legend
            at the top of the graph can be used to toggle the visibility of the data for a particular institute. The graph
            can be zoomed in and out using the mouse wheel or by pinching on a touch screen.
            The graph can also be panned by clicking and dragging on the graph. The data points can be hovered over or
            tapped to see the exact values.
        </p>
        <p class="text-md m-4">
            Try it out now by visiting the <a class="text-indigo-600 hover:text-indigo-900"
                href="{{ route('institute-trends') }}" target="_blank">link</a>.
        </p>

        <h3 class="text-xl font-bold m-4">Analyse Round-wise Cut-off Trends</h3>
        <source srcset="{{ asset('images/dark/josaa-analysis-round-trends.png') }}" media="(prefers-color-scheme: dark)">
        <img class="lg:max-w-3xl m-auto" src="{{ asset('images/light/josaa-analysis-round-trends.png') }}"
            alt="Screenshot of the round trends feature of the JoSAA Analysis tool">
        <p class="text-md m-4">
            This feature allows you to see the trends of cut-offs of a program in an institute throughout the rounds of the
            counselling process. This is useful when you want to see the likelihood of changes in the cut-offs of a program
            in an institute throughout the rounds of the counselling process.
        </p>
        <p class="text-md m-4">
            The data is displayed in a line graph format, with the closing ranks for each round of counselling. The legend
            at the top of the graph can be used to toggle the visibility of the data for a particular institute. The graph
            can be zoomed in and out using the mouse wheel or by pinching on a touch screen.
            The graph can also be panned by clicking and dragging on the graph. The data points can be hovered over or
            tapped to see the exact values.
        </p>
        <p class="text-md m-4">
            Try it out now by visiting the <a class="text-indigo-600 hover:text-indigo-900"
                href="{{ route('round-trends') }}" target="_blank">link</a>.
        </p>
    </article>
    <a class="hidden" href="{{ route('news.amp.using-the-josaa-analysis-tool') }}">View the AMP version of this page</a>
@endsection
