<table class="table table-condensed table-bordered table-hover">
  <thead>
    <tr>
    @forelse ($headers as $header)
      <td>{{ ucwords(str_replace(['_', '-'], ' ', $header)) }}</td>
    @empty
      <td></td>
    @endforelse
    @if (count($data) > 0 && $action)
      <td>Actions</td>
    @endif
    </tr>
  </thead>
  <tbody>
  @forelse ($data as $row)
    <tr>
    @foreach ($row as $value)
      <td>{{ $value }}</td>
    @endforeach
    @if ($action)
      <td>
        <a href="{{ route($base_route . '/edit', $row['id']) }}" class="btn btn-xs btn-default btn-outline">Edit</a>
        <a href="{{ route($base_route . '/delete', $row['id']) }}" class="btn btn-xs btn-danger">Delete</a>
      </td>
    @endif
    </tr>
  @empty
    <tr><td>Data not found</td></tr>
  @endforelse
  </tbody>
</table>

@if ($action)
<a href="{{ route($base_route . '/new') }}" class="btn btn-sm btn-primary">New</a>
@endif