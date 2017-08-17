<table class="table table-condensed table-bordered table-hover">
  <thead>
    <tr>
    @forelse ($headers as $header)
      <td>{{ ucwords($header) }}</td>
    @empty
      <td></td>
    @endforelse
    @if (count($headers) > 0)
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
      <td>
        <a href="{{ route($base_route . '/edit', $row['id']) }}" class="btn btn-xs btn-default btn-outline">Edit</a>
        <a href="{{ route($base_route . '/delete', $row['id']) }}" class="btn btn-xs btn-danger">Delete</a>
      </td>
    </tr>
  @empty
    <tr><td>Data not found</td></tr>
  @endforelse
  </tbody>
</table>

<a href="{{ route($base_route . '/new') }}" class="btn btn-sm btn-primary">New</a>