import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProductosPendientesComponent } from './productos-pendientes.component';

describe('ProductosPendientesComponent', () => {
  let component: ProductosPendientesComponent;
  let fixture: ComponentFixture<ProductosPendientesComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ProductosPendientesComponent]
    });
    fixture = TestBed.createComponent(ProductosPendientesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
