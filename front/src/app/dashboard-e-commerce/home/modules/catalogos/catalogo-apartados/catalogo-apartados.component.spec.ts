import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CatalogoApartadosComponent } from './catalogo-apartados.component';

describe('CatalogoApartadosComponent', () => {
  let component: CatalogoApartadosComponent;
  let fixture: ComponentFixture<CatalogoApartadosComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [CatalogoApartadosComponent]
    });
    fixture = TestBed.createComponent(CatalogoApartadosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
