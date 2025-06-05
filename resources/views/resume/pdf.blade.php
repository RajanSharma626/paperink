<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Ubuntu', sans-serif;
        }

        .page {
            background: #fff;
            width: 210mm;
            min-height: 297mm;
            border-radius: 10px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .top {
            padding: 5mm 10mm;
            line-height: 1.2;
        }

        .top .name {
            font-size: 30px;
            color: #455aa3;
        }

        .top .profession {
            font-size: 18px;
        }

        .top .bio {
            font-size: 14px;
        }

        .middle {
            padding: 1mm 10mm;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .details-section {
            padding: 2mm 0;
        }

        .details {
            font-size: 12px !important;
            font-weight: 400;
            padding-right: 4mm;
        }

        .details .icons {
            padding-right: 8px;
            font-size: 12px !important;
            color: #455aa3;
        }

        .end {
            padding: 2mm 10mm;
        }

        /* Skills Section */
        .skills-table {
            margin-bottom: 2mm;
            line-height: 1.2;
        }

        .skills-table h4 {
            font-size: 18px;
            text-transform: uppercase;
            color: #455aa3;
            margin: 5px 0;
        }

        .skills-section {
            font-size: 14px;
            line-height: 2;
        }

        .skill-tag {
            background-color: #455aa3;
            border-radius: 4px;
            padding: 3px 6px;
            text-align: center;
            margin: 0 5px 0 0;
            color: #fff;
            display: inline-block;
        }

        /* Work Experience Section */
        .work-table {
            margin-top: 4mm;
            margin-bottom: 4mm;
            line-height: 1.2;
        }

        .work-table h4 {
            font-size: 18px;
            text-transform: uppercase;
            color: #455aa3;
            margin: 5px 0;
        }

        .work-item {
            margin-bottom: 4mm;
        }

        .work-item h5 {
            font-size: 16px;
        }

        .work-item h6 {
            font-size: 16px;
            font-weight: 500;
        }

        .date-row {
            font-size: 12px;
            font-style: italic;
            color: #455aa3;
            padding: 4px 0;
        }

        .date-left {
            text-align: left;
        }

        .date-right {
            text-align: right;
        }

        .description {
            font-size: 14px !important;
            padding-top: 2mm;
            padding-bottom: 2mm;
        }

        .description ul {
            padding-left: 4mm;
            line-height: 1.3;
        }

        .description li::marker {
            color: #455aa3;
        }

        /* Education Section */
        .education-table {
            margin-bottom: 4mm;
            line-height: 1.2;
        }

        .education-table h4 {
            font-size: 18px;
            text-transform: uppercase;
            color: #455aa3;
            margin: 5px 0;
        }

        .education-item {
            margin-bottom: 4mm;
        }

        .education-item h5 {
            font-size: 16px;
        }

        .education-item h6 {
            font-size: 16px;
            font-weight: 500;
        }

        .education-item .date {
            font-size: 12px;
            font-style: italic;
            color: #455aa3;
            padding: 3px 0;
        }

        /* Organizations Section */
        .org-table {
            margin-bottom: 4mm;
            line-height: 1.2;
        }

        .org-table h4 {
            font-size: 18px;
            text-transform: uppercase;
            color: #455aa3;
            margin: 5px 0;
        }

        .org-item {
            font-size: 14px;
            padding-bottom: 4mm;
            vertical-align: top;
        }

        /* Languages Section */
        .lang-table {
            margin-bottom: 4mm;
            line-height: 1.2;
        }

        .lang-table h4 {
            font-size: 18px;
            text-transform: uppercase;
            color: #455aa3;
            margin: 5px 0;
        }

        .languages-section {
            border-spacing: 35px 0;
        }

        .lang-item {
            font-size: 14px;
            vertical-align: top;
        }

        .lang-item span {
            font-size: 12px;
            font-style: italic;
            color: #455aa3;
            padding: 3px 0;
        }

        @media print {

            body,
            .page {
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="page border" id="page">
        <table class="main-table" cellpadding="0" cellspacing="0" border="0">
            <!-- Header Section -->
            <tr>
                <td class="top">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="name">{{ $resume->name + ' ' + $resume->lastName }}</td>
                        </tr>
                        <tr>
                            <td class="profession">{{ $resume->jobTitle }}</td>
                        </tr>
                        <tr>
                            <td class="bio">{{ $resume->summary }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Contact Details Section -->
            <tr>
                <td class="middle">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="details-section">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="details"><i class="icons bi-envelope-fill"></i> {{ $resume->email }}
                                        </td>
                                        <td class="details"><i class="icons bi-phone-fill"></i> {{ $resume->phone }}
                                        </td>
                                        <td class="details"><i class="icons bi-geo-alt-fill"></i>
                                            {{ implode(', ', array_filter([$resume->address, $resume->city, $resume->postalCode, $resume->country])) }}
                                        </td>
                                        <td class="details"><i class="icons bi-linkedin"></i> {{ $resume->linkedin }}
                                        </td>
                                        <td class="details"><i class="icons bi bi-skype"></i> {{ $resume->web }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Content Section -->
            <tr>
                <td class="end">
                    <!-- Skills Section -->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="skills-table">
                        <tr>
                            <td>
                                <h4>Skills</h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="skills-section">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            @foreach ($resume->skills as $skill)
                                                <span class="skill-tag">{{ $skill->skill }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    @if ($resume->employmentHistory)
                        <!-- Work Experience Section -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="work-table">
                            <tr>
                                <td>
                                    <h4>Work Experience</h4>
                                </td>
                            </tr>
                            @foreach ($resume->employmentHistory as $job)
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                            class="work-item">
                                            <tr>
                                                <td>
                                                    <h5>{{ $job->jobTitle }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ $job->company }}</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                                        class="date-row">
                                                        <tr>
                                                            <td class="date-left">{{ $job->startDate }} -
                                                                {{ $job->endDate ?? 'Present' }}</td>
                                                            <td class="date-right">{{ $job->city }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="description">{!! nl2br(e($job->description)) !!}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif

                    @if ($resume->education)
                        <!-- Education Section -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="education-table">
                            <tr>
                                <td>
                                    <h4>Education</h4>
                                </td>
                            </tr>
                            @foreach ($resume->education as $edu)
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                            class="education-item">
                                            <tr>
                                                <td>
                                                    <h5>{{ $edu->degree }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ $edu->school }}</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="date">{{ $edu->startDate }} - {{ $edu->endDate }}</td>
                                            </tr>
                                            <tr>
                                                <td class="description">{!! nl2br(e($edu->description)) !!}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                    <!-- Organizations Section -->
                    {{-- <table v-if="resume.orgs && resume.orgs.length" width="100%" cellpadding="0" cellspacing="0" border="0"
            class="org-table">
            <tr>
              <td>
                <h4>Organizations</h4>
              </td>
            </tr>
            <tr>
              <td>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td width="50%" valign="top">
                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr v-for="(org, index) in leftOrgs" :key="'left-' + index">
                          <td class="org-item">
                            {{ org.org }}<br />({{ org.start }} - {{ org.end }})
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td width="50%" valign="top">
                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr v-for="(org, index) in rightOrgs" :key="'right-' + index">
                          <td class="org-item">
                            {{ org.org }}<br />({{ org.start }} - {{ org.end }})
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table> --}}

                    <!-- Languages Section -->
                    {{-- <table v-if="resume.lang && resume.lang.length" width="100%" cellpadding="0" cellspacing="0" border="0"
            class="lang-table">
            <tr>
              <td>
                <h4>Languages</h4>
              </td>
            </tr>
            <tr>
              <td>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td>
                      <table cellpadding="0" cellspacing="0" border="0" class="languages-section">
                        <tr>
                          <td v-for="(language, index) in resume.lang"  class="lang-item">
                            {{ language.lang }} <br /><span>{{ language.lvl }}</span>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table> --}}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
