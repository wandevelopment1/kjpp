<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-admin.head />

<body>

    <x-admin.sidebar />

    <main class="dashboard-main">

        <x-admin.navbar />

        <div class="dashboard-main-body">

            <x-admin.breadcrumb :title="$title ?? ''" :subTitle="$subTitle ?? ''" />

            @yield('content')

        </div>

        <x-admin.footer />

    </main>

    <x-admin.script :script="$script ?? ''" />

</body>

</html>