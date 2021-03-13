<?php namespace App\Http\Controllers\Frontend;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\BaseModel;
use App\Models\MailModel;
use DB,Session,Cart,Whois,Mail,Socialite,Image,Helper;
use Illuminate\Http\Request;
class ShoppingCartController extends Controller {
	public function index(){
		$data['cart'] = Cart::content();
		return view('frontend.shoppingcart.shoppingcart',$data);
	}
	public function checkout(){
		$data['cart'] = Cart::content();
		return view('frontend.shoppingcart.checkout',$data);
	}
	public function destroy(){
		Cart::destroy();
		return redirect(url());
	}
	public function remove($rowid){
		Cart::remove($rowid);
		return redirect()->back();
	}
	public function content_checkout(){
		$cart = Cart::content();
		$result ='';
		$result .='<table border="0" width="100%" class="tblSkin table-responsive" style="border:unset">
		<tbody>
		<tr class="tblSkinHeader" style="border-bottom: 1px solid">

		<td style="border:none"><strong>Tên sản phẩm</strong></td>
		<td align="center" style="border:none"><strong>SL</strong></td>
		<td align="center" style="border:none"><strong>Đ.giá</strong></td>
		<td align="center" style="border:none"><strong>T.tiền</strong></td>
		</tr> ';
		foreach ($cart as  $item) {
			$result .= '<tr class="tblSkinRow" id="671529g0" style="border-bottom: 1px solid">
			<td style="border:none"><div class="right_Option_card">';
			$result .='<strong class="orderName">'.$item->name.'</strong>';
			$result .=' <div class="item-options"><div class="type-item">';
			$result .= 'Phân loại: <strong>'.$item->options->type.'</strong></div></div></div></td>';
			$result .='<td style="border:none">SL: '.$item->qty.'</td>';
			$result .='<td align="center" style="width: 65px;border:none">'.Helper::adddotstring($item->price).'</td>';

			$result .='<td align="center" style="width: 65px;border:none"><span id="total_t_671529">'.Helper::adddotstring($item->price*$item->qty).'</span></td></tr>';
		}
		$result .= '<tr class="tblSkinRow">
                          <td align="left" colspan="2" style="border:none"><span class="totalmoney">Tổng tiền</span></td>
                          <td align="right" colspan="2" style="border:none"><span id="lblTotalPriceShopCart"><span id="lblTotalPrice" >'.Helper::adddotstring(Cart::total()).' </span></span></td>
                        </tr>';
		$result .='</tbody></table>';
		return $result;
	}
	public function payment(Request $request){
		$user = new BaseModel();
		if($request->checkout==1){
			$name = $request->name;
			$address = $request->address;
			$phone = $request->phone;
			$user_id = $request->user_id;
			$total_price = Cart::total();
			$content = json_encode(Cart::content());
			$time = time();
			$arr = [
				'name' => $name,
				'phone' => $phone,
				'address' => $address,
				'time' => $time,
				'user_id' => $user_id,
				'content' => $content,
				'total_price' => $total_price,
				'status' => 1,
			];
			$user->insertData('order',$arr);
			/*Nội dung email */
			$getItem = $user->getFirstRowWhere('order',['time'=>$time]);
			$data = [
				'id'=> $getItem->id,
				'total_price' => $total_price,
				'name' => $name,
				'time' => $time,
				'time' => $getItem->time,
				'address' => $address,
				'phone' =>$phone,
				'content' => $content,
			]; // để chuyền vào view
			Mail::send('emails.checkout',$data,function($msg) use ($name){
				$msg->from(env('MAIL_USERNAME'),$name);
				$user = new BaseModel();
				@$site_option = $user->getFirstRowWhere('site_option',[]);
				$msg->to(@$site_option->email)->subject('ĐƠN HÀNG MỚI');

			});
			Session::flash('success','Đặt hàng thành công! ');
			Cart::destroy();
			return redirect(url());
		}

	}
}
