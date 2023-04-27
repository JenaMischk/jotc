import { AfterViewInit, Component, ViewChild, ChangeDetectorRef } from '@angular/core';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { MatTable } from '@angular/material/table';
import { DashboardDataSource, DashboardItem } from './dashboard-datasource';
import { DataService } from 'src/app/services/data.service';
import { tap, merge } from 'rxjs';
import { FormBuilder } from '@angular/forms';
import { distinctUntilChanged, debounceTime } from 'rxjs/operators';

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

  searchForm = this.fb.group({
    filter: null,
    dateBegin: null,
    dateEnd: null
  });

  constructor(
    private dataService: DataService,
    private fb: FormBuilder
  ) {
    this.dataSource = new DashboardDataSource(dataService);
  }

  ngAfterViewInit(): void {
    this.dataSource.sort = this.sort;
    this.dataSource.paginator = this.paginator;
    this.dataSource.filter = this.searchForm.get('filter')?.value;
    this.table.dataSource = this.dataSource;

    // reset the paginator after sorting
    this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);
      
    merge(this.sort.sortChange, this.paginator.page)
      .pipe(
          tap(() => this.dataSource.getSubmissions())
      )
      .subscribe();

    this.searchForm.controls['filter'].valueChanges.pipe(
      debounceTime(400),
      distinctUntilChanged()
    ).subscribe(change => {
      this.dataSource.filter = this.searchForm.get('filter')?.value;
      this.dataSource.getSubmissions();
    });

    this.searchForm.controls['dateBegin'].valueChanges.pipe(
      debounceTime(400),
      distinctUntilChanged()
    ).subscribe(change => {
      this.dataSource.dateBegin = this.searchForm.get('dateBegin')?.value;
      this.dataSource.getSubmissions();
    });

    this.searchForm.controls['dateEnd'].valueChanges.pipe(
      debounceTime(400),
      distinctUntilChanged()
    ).subscribe(change => {
      this.dataSource.dateEnd = this.searchForm.get('dateEnd')?.value;
      this.dataSource.getSubmissions();
    });

    this.dataSource.getSubmissions();
  }

}
