import { AfterViewInit, Component, ViewChild, ChangeDetectorRef } from '@angular/core';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { MatTable } from '@angular/material/table';
import { DashboardDataSource, DashboardItem } from './dashboard-datasource';
import { DataService } from 'src/app/services/data.service';
import { tap, merge } from 'rxjs';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements AfterViewInit{
  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;
  @ViewChild(MatTable) table!: MatTable<DashboardItem>;
  dataSource: DashboardDataSource;

  /** Columns displayed in the table. Columns IDs can be added, removed, or reordered. */
  displayedColumns = ['id', 'user_email', 'input', 'output', 'date'];

  constructor(
    private dataService: DataService
  ) {
    this.dataSource = new DashboardDataSource(dataService);
  }

  ngAfterViewInit(): void {
    this.dataSource.sort = this.sort;
    this.dataSource.paginator = this.paginator;
    this.table.dataSource = this.dataSource;

    // reset the paginator after sorting
    this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);
      
    merge(this.sort.sortChange, this.paginator.page)
      .pipe(
          tap(() => this.dataSource.getSubmissions())
      )
      .subscribe();

    this.dataSource.getSubmissions();
  }

}
