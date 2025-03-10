<div class="card mb-3">
    <div class="card-body">
        <form id="searchForm" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Search...">
            </div>
            <div class="col-md-3">
                <select class="form-control" name="per_page">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" name="sort_by">
                    <option value="created_at">Created Date</option>
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="sort_direction">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
            </div>
        </form>
    </div>
</div> 