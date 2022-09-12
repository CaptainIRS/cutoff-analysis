@extends('layouts.news.amp')

@section('meta')
    <meta name="description"
        content="How to use JoSAA Analysis - a web application that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <link rel="preload" media="(prefers-color-scheme: dark)"
        href="{{ asset('images/dark/josaa-analysis-institute-trends.png') }}" as="image">
    <link rel="preload" media="(prefers-color-scheme: light)"
        href="{{ asset('images/light/josaa-analysis-program-trends.png') }}" as="image">
    <link rel="canonical" href="{{ route('news.using-the-josaa-analysis-tool') }}">
    <title>Using the JoSAA Analysis tool | News &amp; Updates | {{ config('app.name') }}</title>
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Article",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ route('news.using-the-josaa-analysis-tool') }}"
      },
      "headline": "Using the JoSAA Analysis tool",
      "image": [
        "{{ asset('images/dark/josaa-analysis-institute-trends.png') }}"
      ],
      "datePublished": "2022-09-13T00:00:00+05:30",
      "dateModified": "2022-09-13T00:00:00+05:30",
      "author": {
        "@type": "Person",
        "name": "CaptainIRS",
        "url": "https://github.com/CaptainIRS"
      },
      "description": "How to use JoSAA Analysis - a web application that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data."
    }
    </script>
@endsection

@section('content')
    <article>
        <h1>Using the JoSAA Analysis tool</h1>
        <h5>Published on 13th September 2022</h5>
        <amp-img media="(prefers-color-scheme: dark)" src="{{ asset('images/dark/josaa-analysis-institute-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the institute trends feature of the JoSAA Analysis tool">
        </amp-img>
        <amp-img media="(prefers-color-scheme: light)" src="{{ asset('images/light/josaa-analysis-institute-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the institute trends feature of the JoSAA Analysis tool">
        </amp-img>

        <p>
            The JoSAA counselling process is a very important part of the engineering admission process. It is the time when
            students decide which college they want to go to and which branch they want to study. The process for 2022 has
            already started and will continue till the 21st of October as per the <a href="https://josaa.nic.in/schedule/"
                target="_blank">official schedule</a>.
        </p>

        <p>
            During the counselling process, students have to select and arrange their choices for the colleges and branches
            they want to go to. This requires a lot of research and analysis of the previous year cut-offs, so that they
            can make an informed decision. The official data provided by JoSAA is not very easy to understand and requires
            a lot of time to analyse as it is not presented in a very user-friendly manner. This is where the JoSAA Analysis
            tool comes in.
        </p>

        <p>
            The JoSAA Analysis tool is a web application that helps you decide your choices for JoSAA counselling based on
            10 years of cut-off data. It is a very simple and easy to use tool, which can be used by anyone. It is
            completely free and open source, and you can find the source code on <a
                href="https://github.com/CaptainIRS/josaa-analysis" target="_blank">GitHub</a>.
        </p>

        <p>
            The tool offers ways to filter and visualise the past cut-off data, using the parameters provided by the user
            like branch preference, institute preference, institute type, quota, seat type, gender, etc. The data is
            obtained from the official JoSAA website, and will be updated every year. Data is available for 2012, 2013,
            2014, 2015, 2016, 2017, 2018, 2019, 2020 and 2021, and includes the opening and closing ranks for all the rounds
            of counselling. This helps users to get a better idea of the trends in the cut-offs over the years, and make a
            more informed decision about their choices.
        </p>

        <p>
            Get started by visiting the <a href="{{ route('home') }}" target="_blank">JoSAA Analysis tool</a>.
        </p>


        <p>
            Here are the features of the JoSAA Analysis tool and how to use them:
        </p>

        <h3>Filter by Institute</h3>
        <amp-img media="(prefers-color-scheme: dark)"
            src="{{ asset('images/dark/josaa-analysis-filter-by-institute.png') }}" width="500" height="250"
            layout="responsive" alt="Screenshot of the filter by institute feature of the JoSAA Analysis tool">
        </amp-img>
        <amp-img media="(prefers-color-scheme: light)"
            src="{{ asset('images/light/josaa-analysis-filter-by-institute.png') }}" width="500" height="250"
            layout="responsive" alt="Screenshot of the filter by institute feature of the JoSAA Analysis tool">
        </amp-img>
        <p>
            This feature allows you to filter the past cut-offs by institute. Select one or many institutes using the
            dropdown menu and then further narrow down the data using the program data. Users can also specify the minimum
            and maximum cut-off rank to be displayed so that they can get an idea of the programs in the selected institutes
            that might be suitable for their rank range.
        </p>
        <p>
            The data is displayed in a table format, with the opening and closing ranks for each round of counselling. The
            table can be sorted by any column by clicking on the column header.
        </p>
        <p>
            Try it out now by visiting the <a href="{{ route('search-by-institute') }}" target="_blank">link</a>.
        </p>

        <h3>Filter by Program</h3>
        <amp-img media="(prefers-color-scheme: dark)" src="{{ asset('images/dark/josaa-analysis-filter-by-program.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the filter by program feature of the JoSAA Analysis tool">
        </amp-img>
        <amp-img media="(prefers-color-scheme: light)"
            src="{{ asset('images/light/josaa-analysis-filter-by-program.png') }}" width="500" height="250"
            layout="responsive" alt="Screenshot of the filter by program feature of the JoSAA Analysis tool">
        </amp-img>
        <p>
            This feature allows you to filter the past cut-offs by branch. Select one or many branches using the dropdown
            menu and then further narrow down the data using the institute data. The branches available in the dropdown are
            fields of engineering like Computer Science and IT, Civil, etc. and aggregates like Circuital and Non-Circuital.
            Users can also specify the minimum and maximum cut-off rank to be displayed so that they can get an idea of the
            institutes offering the selected branches that might be suitable for their rank range.
        </p>
        <p>
            The data is displayed in a table format, with the opening and closing ranks for each round of counselling. The
            table can be sorted by any column by clicking on the column header.
        </p>
        <p>
            Try it out now by visiting the <a href="{{ route('search-by-program') }}" target="_blank">link</a>.
        </p>

        <h3>Branch Trends</h3>
        <amp-img media="(prefers-color-scheme: dark)" src="{{ asset('images/dark/josaa-analysis-branch-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the branch trends feature of the JoSAA Analysis tool">
        </amp-img>
        <amp-img media="(prefers-color-scheme: light)" src="{{ asset('images/light/josaa-analysis-branch-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the branch trends feature of the JoSAA Analysis tool">
        </amp-img>
        <p>
            This feature allows you to visualise the trends of cut-offs of various institutes offering courses in a
            particular branch of engineering over the years. This gives an idea about the popularity and perception of
            various institutes offering courses in a branch of engineering among engineering aspirants.
        </p>
        <p>
            The data is displayed in a line graph format, with the closing ranks for each round of counselling. The legend
            at the top of the graph can be used to toggle the visibility of the data for a particular institute. The graph
            can be zoomed in and out using the mouse wheel or by pinching on a touch screen.
            The graph can also be panned by clicking and dragging on the graph. The data points can be hovered over or
            tapped to see the exact values.
        </p>
        <p>
            Try it out now by visiting the <a href="{{ route('branch-trends') }}" target="_blank">link</a>.
        </p>

        <h3>Institute Trends</h3>
        <amp-img media="(prefers-color-scheme: dark)" src="{{ asset('images/dark/josaa-analysis-institute-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the institute trends feature of the JoSAA Analysis tool">
        </amp-img>
        <amp-img media="(prefers-color-scheme: light)"
            src="{{ asset('images/light/josaa-analysis-institute-trends.png') }}" width="500" height="250"
            layout="responsive" alt="Screenshot of the institute trends feature of the JoSAA Analysis tool">
        </amp-img>
        <p>
            This feature allows you to visualise the trends of cut-offs of various branches offered by an institute over the
            years. This gives an idea about the popularity and perception of various branches offered by an institute among
            engineering aspirants.
        </p>
        <p>
            The data is displayed in a line graph format, with the closing ranks for each round of counselling. The legend
            at the top of the graph can be used to toggle the visibility of the data for a particular institute. The graph
            can be zoomed in and out using the mouse wheel or by pinching on a touch screen.
            The graph can also be panned by clicking and dragging on the graph. The data points can be hovered over or
            tapped to see the exact values.
        </p>
        <p>
            Try it out now by visiting the <a href="{{ route('institute-trends') }}" target="_blank">link</a>.
        </p>

        <h3>Program Trends</h3>
        <amp-img media="(prefers-color-scheme: dark)" src="{{ asset('images/dark/josaa-analysis-program-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the program trends feature of the JoSAA Analysis tool">
        </amp-img>
        <amp-img media="(prefers-color-scheme: light)" src="{{ asset('images/light/josaa-analysis-program-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the program trends feature of the JoSAA Analysis tool">
        </amp-img>
        <p>
            This feature allows you to visualise the trends of cut-offs of various institutes offering a particular program
            over the years. This gives an idea about the popularity and perception of various institutes offering a program
            among engineering aspirants.
        </p>
        <p>
            The data is displayed in a line graph format, with the closing ranks for each round of counselling. The legend
            at the top of the graph can be used to toggle the visibility of the data for a particular institute. The graph
            can be zoomed in and out using the mouse wheel or by pinching on a touch screen.
            The graph can also be panned by clicking and dragging on the graph. The data points can be hovered over or
            tapped to see the exact values.
        </p>
        <p>
            Try it out now by visiting the <a href="{{ route('program-trends') }}" target="_blank">link</a>.
        </p>

        <h3>Round Trends</h3>
        <amp-img media="(prefers-color-scheme: dark)" src="{{ asset('images/dark/josaa-analysis-round-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the round trends feature of the JoSAA Analysis tool">
        </amp-img>
        <amp-img media="(prefers-color-scheme: light)" src="{{ asset('images/light/josaa-analysis-round-trends.png') }}"
            width="500" height="250" layout="responsive"
            alt="Screenshot of the round trends feature of the JoSAA Analysis tool">
        </amp-img>
        <p>
            This feature allows you to see the trends of cut-offs of a program in an institute throughout the rounds of the
            counselling process. This is useful when you want to see the likelihood of changes in the cut-offs of a program
            in an institute throughout the rounds of the counselling process.
        </p>
        <p>
            The data is displayed in a line graph format, with the closing ranks for each round of counselling. The legend
            at the top of the graph can be used to toggle the visibility of the data for a particular institute. The graph
            can be zoomed in and out using the mouse wheel or by pinching on a touch screen.
            The graph can also be panned by clicking and dragging on the graph. The data points can be hovered over or
            tapped to see the exact values.
        </p>
        <p>
            Try it out now by visiting the <a href="{{ route('round-trends') }}" target="_blank">link</a>.
        </p>
    </article>
@endsection
