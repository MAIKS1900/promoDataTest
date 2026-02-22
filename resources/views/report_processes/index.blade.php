@php use App\Domain\ReportStatusesInterface; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Контроль выполнения процессов</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 p-6">
<div class="max-w-6xl mx-auto bg-white rounded-lg shadow">
    <div class="px-6 py-4 bg-gray-100">
        <h1 class="text-xl font-semibold text-gray-900">Контроль выполнения процессов</h1>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider">Дата процесса</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider">Время выполнения</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider">PID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider">Статус</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider">Файл</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @php /** @var \App\Models\ReportProcess $process */ @endphp
            @forelse ($processes as $process)
                <tr class="hover:bg-gray-50 {{ $process->processStatus->ps_id === ReportStatusesInterface::ERROR ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $process->rp_start_datetime->format('d.m.Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $process->rp_exec_time }} сек.
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $process->rp_pid }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($process->processStatus->ps_id === ReportStatusesInterface::DONE->value)
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-300 text-green-800">
                                {{ $process->processStatus->ps_name }}
                            </span>
                        @elseif ($process->processStatus->ps_id === ReportStatusesInterface::ERROR->value)
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-800">
                                {{ $process->processStatus->ps_name }}
                            </span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-200 text-blue-800">
                                {{ $process->processStatus->ps_name }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if ($process->processStatus->ps_name === 'Завершен' && $process->rp_file_save_path)
                            <a href="{{ Storage::disk('reports')->url($process->rp_file_save_path) }}" target="_blank"
                               class="text-blue-600 hover:text-blue-900">
                                Скачать
                            </a>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                        Нет данных о процессах
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
